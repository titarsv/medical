<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Validator;

use App\Models\User;
use App\Models\Settings;
use App\Models\Image;
use App\Models\UserData;
use App\Models\Order;
use App\Models\ProductsInOrder;
use App\Models\Wishlist;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Cart;
use App\Models\ProductsCart;
use Breadcrumbs;
use Mail;

use Cartalyst\Sentinel\Roles\EloquentRole as Roles;

class UserController extends Controller
{
    private $users;
    public $settings;

    protected $rules = [
        'email' => 'required|unique:users'
    ];
    protected $messages = [
        'email.required' => 'Поле должно быть заполнено!',
        'email.unique' => 'Поле должно быть уникальным!'
    ];

    public function __construct()
    {
        //
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::join('role_users', function ($join) {
                $join->on('users.id', '=', 'role_users.user_id')
                    ->whereIn('role_users.role_id', [3,4]);
                })
                ->get();

        return view('admin.users.index')->with('users', $users)->with('title', 'Список пользователей');
    }

    /**
     * @return mixed
     */
    public function managers()
    {
        $user_role = Sentinel::findRoleBySlug('manager');
        $users = $user_role->users()->with('roles')->get();

        return view('admin.users.index')->with('users', $users)->with('title', 'Список менеджеров');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Sentinel::check();

        $user = User::find($user->id);

        $orders = Order::where('user_id',$user->id)->orderBy('created_at','desc')->get();

        $wish_list = Wishlist::where('user_id',$user->id)->get();

        return view('users.index')->with('user', $user)
            ->with('orders', $orders)
            ->with('wish_list', $wish_list);
    }
//    public function history()
//    {
//        $user = Sentinel::check();
//        $orders = Order::where('user_id',$user->id)->orderBy('created_at','desc')->get();
////        return dd($orders);
//        return view('users.history')->with('user', $user)->with('orders', $orders);
//    }
//    public function wishList()
//    {
//        $user = Sentinel::check();
//        $wish_list = Wishlist::where('user_id',$user->id)->get();
//        return view('users.wishlist')->with('user', $user)->with('wish_list', $wish_list);
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $settings = Settings::find(1);

        $image_size = [
            'width' => $settings->category_image_width,
            'height' => $settings->category_image_height
        ];

        $user = User::find($id);

//        return dd($user);
        return view('admin.users.edit')
            ->with('user', $user)
            ->with('image_size', $image_size);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = $this->rules;
        $rules['email'] = 'required|unique:users,email,'.$id.'';

        $messages = $this->messages;

        $validator = Validator::make($request->all(), $rules, $messages);
        $user = User::find($id);

        $image_id = $request->image_id ? $request->image_id : $user->user_data->image_id;
        $href = Image::find($image_id)->href;

        $request->merge(['href' => $href, 'user_id' => $id]);
//return dd($request);
        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $request_user = $request->only(['first_name', 'last_name', 'email']);
        $user->fill($request_user);
        $user->save();

        $request_user_data = $request->only(['user_id', 'phone', 'adress', 'company', 'other_data', 'image_id']);
        $user->user_data()->update($request_user_data);


        /*
         *  надо поменять сентинел на что то другое!
         *
         *
         */

        $user_role = Sentinel::findRoleBySlug('user');
        $unreg_user_role = Sentinel::findRoleBySlug('unregister_user');
        $unreg_users = $unreg_user_role->users()->with('roles')->get();
        $users = $user_role->users()->with('roles')->get();
        $users = $users->merge($unreg_users);

        return redirect('/admin/users')
            ->with('users', $users)
            ->with('message-success', 'Пользователь ' . $user->first_name . ' успешно обновлен.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

//    public function changeData()
//    {
//        $user = Sentinel::check();
//        if ($user) {
//            $user = User::find($user->id);
//        }
//
//        return view('users.change_data')
//            ->with('user', $user);
//    }

    public function saveChangedData(Request $request)
    {
        $user = Sentinel::check();
        if ($user) {
            $user = User::find($user->id);
        }

        $rules = [
            'first_name' => 'required',
            'phone'     => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'email'     => 'required|email|unique:users,email,'.$user->id
        ];

        $messages = [
            'first_name.required' => 'Не заполнены обязательные поля!',
            'phone.required'    => 'Не заполнены обязательные поля!',
            'phone.regex'       => 'Некорректный телефон!',
            'email.required'    => 'Не заполнены обязательные поля!',
            'email.email'       => 'Некорректный email-адрес!',
            'email.unique'      => 'Пользователь с таким email-ом уже зарегистрирован!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        $user->first_name = htmlspecialchars($request->first_name);
        $user->last_name = htmlspecialchars($request->last_name);
        $user->email = htmlspecialchars($request->email);
        $user->user_data->phone = htmlspecialchars($request->phone);
        $user->user_data->adress = htmlspecialchars($request->adress);

        $user->push();

        return redirect('/user')
            ->with('status', 'Ваши личные данные успешно изменены!')
            ->with('process', 'change_data');
    }

    public function updatePassword(Request $request)
    {
        $user = Sentinel::check();
        if ($user) {
            $user = User::find($user->id);
        }

        $rules = [
            'password'  => 'min:6|confirmed',
            'password_confirmation' => 'min:6'
        ];

        $messages = [
            'password.min'      => 'Пароль должен быть не менее 6 символов!',
            'password.confirmed' => 'Введенные пароли не совпадают!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('process', 'update_password');
        }

        if($request->password) {
            $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        }

        $user->push();

        return redirect('/user')
            ->with('status', 'Ваш пароль успешно изменён!')
            ->with('process', 'update_password');
    }

    public function subscribe(Request $request, User $user, UserData $user_data)
    {
        $rules = [
            'email'     => 'required|email'
        ];

        $messages = [
            'email.required'    => 'Вы не указали email-адрес!',
            'email.email'       => 'Некорректный email-адрес!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $user_exists = User::where('email', $request->email)->first();

        if ($user_exists){
            $subscribe = $user->where('id', $user_exists->id)->first();
            $subscribe->user_data->subscribe = 1;
            $subscribe->save();
        } else {
            $user = Sentinel::registerAndActivate(array(
                'email'    => $request->email,
                'password' => 'null',
                'permissions' => [
                    'unregistered' => true
            ]
            ));

            $role = Sentinel::findRoleBySlug('unregister_user');
            $role->users()->attach($user);

            $user_data->create([
                'user_id'   => $user->id,
                'image_id'  => 1,
                'subscribe' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        return response()->json(['success' => 'Вы успешно подписались на новости!']);
    }

    public function fromCartToOrder($cart_data)
    {

    }

    public function statistic($id)
    {
        $orders = Order::where('user_id', $id)->get();

        return view('admin.users.orders')->with('orders', $orders)->with('user', User::find($id));
    }
    public function reviews($id)
    {
        $reviews = Reviews::where('user_id', $id)->paginate(10);

        return view('admin.users.reviews')->with('reviews', $reviews)->with('user', User::find($id));
    }
    public function adminWishlist($id)
    {
        $wishlist = Wishlist::where('user_id', $id)->paginate(10);

        return view('admin.users.wishlist')->with('wishlist', $wishlist)->with('user', User::find($id));
    }

    public function fuck()
    {
       $reviews = User::find(3);
        return dd($reviews->reviews);
    }

    /**
     * Заказ обратного звонка
     *
     * @param Request $request
     */
    public function callback(Request $request, Settings $settings){
        $rules = [
            'name' => 'required',
            'phone'     => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/'
        ];

        $messages = [
            'name.required' => 'Не указано имя!',
            'phone.required'    => 'Некорректный номер телефона!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        }

        $emails = $settings->get_setting('notify_emails');

        Mail::send('emails.callback', ['name' => $request->name, 'phone' => $request->phone], function($msg) use ($emails){
            $msg->from('ua-tuning@ua-tuning.com.ua', 'UA Tuning');
            $msg->to($emails);
            $msg->subject('Перезвоните мне!');
        });

        return response()->json(['success' => 'Спасибо за Ваш интерес к нам! Наш менеджер вскоре свяжется с вами, ожидайте звонка.']);
    }

    public function sendMail(Settings $setting){
        $domain = $_SERVER['HTTP_HOST'];
        $_SESSION['http_host'] = $domain;

        $domain = 'lab-oborud.com';
        $from = "info@$domain";
        $title = '';

        $subject = "Заявка $domain " . $title;

        if(count($_FILES)){
            //print_r($_FILES);
            $files = array();
            foreach ($_FILES as $file) {
                if ($file["error"] == 0) {
                    $tmp_name = $file["tmp_name"];
                    // basename() может спасти от атак на файловую систему;
                    // может понадобиться дополнительная проверка/очистка имени файла
                    $name = basename($file["name"]);
                    move_uploaded_file($tmp_name, "upload/$name");
                    $files[] = array('path' => "upload/$name", 'name' => $tmp_name);
                }
            }
        }

        if (array_key_exists('data', $_POST)){
            //return print_r($_POST);die();

            $eol = PHP_EOL;

            $headers = "From: $from\nReply-To: $from\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html;charset=utf-8 \r\n";

            $msg = "";

            $msg .= "<html><body style='font-family:Arial,sans-serif;'>";
            $msg .= "<h2 style='color:#161616;font-weight:bold;font-size:30px;border-bottom:2px dotted #bd0707;'>Новая заявка на сайте $domain " . $title . "</h2>" . $eol;

            $data = json_decode($_POST['data']);
            $session_data = ['sourse' => 'Поисковая система', 'term' => 'Ключ', 'campaign' => 'Кампания'];

//            if (!isset($data->phone) || empty($data->phone->val)) {
//                header("HTTP/1.0 404 Not Found");
//                echo '{"status":"error", "message":"Не заполнено поле телефон"}';
//                die();
//            }

            foreach ($data as $key => $params) {
                if (!empty($params->title) && !empty($params->val)) {
                    $val = $this->prepare_data($params->val, $key);
                    $msg .= "<p><strong>$params->title:</strong> $val</p>" . $eol;
                    if (isset($session_data[$key]))
                        unset($session_data[$key]);
                }
                if(empty($params->val)){
                    $stat[$key] = 'Лось!';
                }else{
                    $stat[$key] = $this->prepare_data($params->val, $key);
                }

            }

            foreach ($session_data as $key => $title) {
                if (array_key_exists($key, $_SESSION)) {
                    $val = $this->prepare_data($_SESSION[$key], $key);
                    $msg .= "<p><strong>$title:</strong> $val</p>" . $eol;
                }
            }

            $msg .= "</body></html>";

            $emails = get_object_vars($setting->get_setting('notify_emails'));

            $success = true;

            foreach ($emails as $email){
                if(!mail($email, $subject, $msg, $headers)){
                    $success = false;
                }
            }

            if($success ){
                header("HTTP/1.0 200 OK");
                echo '{"status":"success"}';
            }else{
                header("HTTP/1.0 404 Not Found");
                echo '{"status":"error"}';
            }

        } else {
            header("HTTP/1.0 404 Not Found");
            echo '{"status":"error2"}';
        }

    }

    public function prepare_data($data, $key){
        switch ($key) {
            case 'referer':
                return substr($data, 0, 30);
            case 'term':
                return urldecode($data);
            default:
                return $data;
        }
    }

    public function send_mail($to, $thm, $html, $path) {
        $fp = fopen($path,"r");
        if (!$fp) {
            print "Файл $path не может быть прочитан";
            exit();
        }

        $file = fread($fp, filesize($path));
        fclose($fp);

        $boundary = "--".md5(uniqid(time())); // генерируем разделитель
        $headers .= "MIME-Version: 1.0\n";
        $headers .="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
        $multipart .= "--$boundary\n";

        $kod = 'utf-8';
        $multipart .= "Content-Type: text/html; charset=$kod\n";
        $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
        $multipart .= "$html\n\n";

        $message_part = "--$boundary\n";
        $message_part .= "Content-Type: application/octet-stream\n";
        $message_part .= "Content-Transfer-Encoding: base64\n";
        $message_part .= "Content-Disposition: attachment; filename = \"".$path."\"\n\n";
        $message_part .= chunk_split(base64_encode($file))."\n";
        $multipart .= $message_part."--$boundary--\n";

        if(mail($to, $thm, $multipart, $headers)) {
            return 1;
        }
    }
}
