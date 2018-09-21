<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Requests;
use Validator;
use App\Models\User;
use App\Models\UserData;
use Carbon\Carbon;
use App\Models\Settings;
use Mail;

class ReviewsController extends Controller
{
    public function index()
    {
        $all_reviews = Review::orderBy('updated_at', 'desc')->paginate(20);

        $new_reviews = [];
        $reviews = [];

        foreach ($all_reviews as $review) {
            if($review->new) {
                $new_reviews[] = $review;
            } else {
                $reviews[] = $review;
            }
        }

        return view('admin.reviews.index', [
            'new'           => $new_reviews,
            'reviews'       => $reviews,
            'all_reviews'   => $all_reviews
        ]);
    }

    public function show($id)
    {
        $review = Review::find($id);
        return view('admin.reviews.show', ['review' => $review]);
    }


    public function update(Request $request, $id)
    {

        $review = Review::find($id);

        if($request->published && $review->new) {
            $grades = Review::where('product_id', $review->product_id)
                ->where('published', 1)
                ->pluck('grade');

            if(!$grades->isEmpty()) {
                $sum = 0;
                foreach ($grades as $grade) {
                    $sum += $grade;
                }

                $average = $sum / $grades->count();
                $rating = round($average, 2, PHP_ROUND_HALF_UP);
            } elseif (!is_null($review->grade)) {
                $rating = $review->grade;
            } else {
                $rating = null;
            }

            $product = Products::find($request->product_id);
            $product->update(['rating' => $rating]);

        }

        $review->update(['published' => $request->published, 'answer' => $request->answer,  'new' => 0]);

        return redirect('/admin/reviews')
            ->with('message-success', 'Отзыв успешно обновлен!');
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        $review->delete();
        return redirect('/admin/reviews')
            ->with('message-success', 'Отзыв успешно удален!');
    }

    public function add(Request $request, Review $review, UserData $user_data, Settings $setting)
    {
        $rules = [
            'review' => 'required',
            'name' => 'required',
            'email' => 'required|email',
        ];

        $messages = [
            'review.required'   => 'Оставьте текст отзыва!',
            'name.required'     => 'Введите имя!',
            'email.required'    => 'Введите email!',
            'email.email'       => 'Введите корректный email-адрес!'
        ];

        if($request->type == 'review') {
            $rules['grade'] = 'required';
            $messages['grade.required'] = 'Вы не поставили оценку!';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages(), 'type' => $request->type], 200);
        }

        $user = User::where('email', $request->email)->first();

        if($user == null) {
            $user = Sentinel::registerAndActivate(array(
                'email'    => $request->email,
                'password' => 'null',
                'first_name' => $request->name,
                'permissions' => [
                    'unregistered' => true
                ]
            ));

            $role = Sentinel::findRoleBySlug('unregister_user');
            $role->users()->attach($user);

            $user_data->create([
                'user_id'   => $user->id,
                'image_id'  => 1,
                'subscribe' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $review->fill($request->except('_token'));
        $review->user_id = $user->id;
        $review->published = 0;
        $review->new = 1;
        $review->save();

//        $emails = $setting->get_setting('notify_emails');
//
//        Mail::send('emails.review', ['emails', $emails], function($msg) use ($emails){
//            $msg->from('info@lab-oborud.com', 'Сайт lab-oborud.com');
//            $msg->to($emails);
//            $msg->subject('Новый отзыв на сайте!');
//        });

        return response()->json(['success' => 'Ваш отзыв успешно добавлен! Он появится на сайте после проверки администратором!', 'type' => $request->type]);
    }

    public function addLikes(Request $request){
        $user = Sentinel::check();

        if(!$user)
            return response()->json(['error' => 'Незарегистрированные пользователи не могут ставить оценки!']);

        $action = $request->action;
        $review = Review::findOrFail($request->review_id);

        $users = [
            'like'  => json_decode($review->like, true),
            'dislike' => json_decode($review->dislike, true)
        ];

        if(is_null($users[$action]))
            $users[$action] = [];

        if(in_array($user->id, $users[$action])){
            return response()->json(['error' => 'Вы уже проголосовали за этот отзыв!']);
        } elseif ($user->id == $review->user_id){
            return response()->json(['error' => 'Вы являетесь автором отзыва!']);
        } else {
            $users[$action][] = $user->id;
            $review->$action = json_encode($users[$action]);
            $count[$action] = count($users[$action]);

            unset($users[$action]);
            $other_action = array_keys($users);
            $other_action = $other_action[0];

            if(!is_null($users[$other_action])) {
                foreach ($users[$other_action] as $key => $other_user) {
                    if ($other_user == $user->id) {
                        unset($users[$other_action][$key]);
                    }
                }
            }
            $count[$other_action] = count($users[$other_action]);

            $review->$other_action = json_encode($users[$other_action]);
            $review->update();

            return response()->json(['success' => 'Спасибо, Ваш отзыв учтен!', 'like' => $count['like'], 'dislike' => $count['dislike']]);
        }

    }
}
