<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use App\Http\Requests;
use Validator;
use App\Models\UserData;
use Mail;
use Carbon\Carbon;
use App\Http\Controllers\CartController;

class LoginController extends Controller
{
    /**
     * Страница авторизации/регистрации
     * @return mixed
     */
    public function login()
    {
        if (Sentinel::check()) {
            $user_id = Sentinel::check()->id;
            CartController::cartToUser($user_id);
            if (Sentinel::inRole('admin') or Sentinel::inRole('manager')) {
                return redirect('/admin');
            } elseif (Sentinel::inRole('user')) {
                return redirect('/user');
            }
        } else {
            return view('login')->with('process', 'authenticate');
        }
    }

    /**
     * Процесс авторизации
     * @param Request $request
     * @return mixed
     */
    public function authenticate(Request $request)
    {
        //return dd($request);
        $rules = [
            'email'     =>'required|email',
            'password'  => 'required',
        ];

        $messages = [
            'email.required'    => 'Введите email-адрес!',
            'email.email'       => 'Некорректный email-адрес!',
            'password.required' => 'Введите свой пароль!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('process', 'authenticate');
        }

        $credentials = [
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
        ];

        $user = Sentinel::findByCredentials($credentials);
        if(!$user){
            return redirect()
                ->back()
                ->withErrors(['email' => 'Пользователь с таким email-адресом не зарегистрирован!'])
                ->withInput($request->except('password'))
                ->with('process', 'authenticate');
        }

        $auth = Sentinel::authenticateAndRemember($credentials);
        if(!$auth){
            return redirect()
                ->back()
                ->withErrors(['password' => 'Неверный пароль!'])
                ->withInput($request->except('password'))
                ->with('process', 'authenticate');
        }
        if(isset($request->referrer) and $request->referrer == '/cart'){
            return redirect('/cart');
        }
        return redirect ('/login')
            ->with('process', 'authenticate');
    }

//    public function register()
//    {
//        if (Sentinel::check()) {
//            return redirect('/user');
//        } else {
//            return view('register');
//        }
//    }

    /**
     * Процесс регистрации
     * @param Request $request
     * @param UserData $user_data
     * @return mixed
     */
    public function store(Request $request, UserData $user_data)
    {
        $rules = [
            'first_name' => 'required',
            'phone'     => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'email'     =>'required|email',
            'password'  => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ];

        $messages = [
            'first_name.required' => 'Не заполнены обязательные поля!',
            'phone.required'    => 'Не заполнены обязательные поля!',
            'phone.regex'       => 'Некорректный телефон!',
            'email.required'    => 'Не заполнены обязательные поля!',
            'email.email'       => 'Некорректный email-адрес!',
            'password.required' => 'Не заполнены обязательные поля!',
            'password.min'      => 'Пароль должен быть не менее 6 символов!',
            'password.confirmed' => 'Введенные пароли не совпадают!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('process', 'registration');
        }

        $user_exists = User::where('email', $request->email)->first();

        if ($user_exists){
            $user = Sentinel::findById($user_exists->id);
            if($user->inRole('unregister_user')){

                $credentials = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'password' => $request->password,
                    'permissions' => [
                        'user' => true
                    ]
                ];

                Sentinel::update($user, $credentials);

                $role = Sentinel::findRoleBySlug('unregister_user');
                $role->users()->detach($user);

                $userRole = Sentinel::findRoleBySlug('user');
                $userRole->users()->attach($user);

                $data = $user_data->where('user_id', $user->id)->first();
                $data->phone = $request->phone;
                $data->save();

                $credentials['email'] = $request->email;
                $auth = Sentinel::authenticateAndRemember($credentials);
                $user_id = $user_exists->id;
                if($auth){

                    CartController::cartToUser($user_id);
                    return redirect('/user')
                        ->with('status', 'Вы успешно зарегистрированы! Добро пожаловать в личный кабинет');
                } else {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['error' => 'При регистрации произошла ошибка!'])
                        ->with('process', 'registration');
                }

            } else {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['email' => 'Пользователь с таким email-ом уже зарегистрирован!'])
                    ->with('process', 'registration');
            }
        }

        $credentials = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email'     => $request->email,
            'password'  => $request->password
        ];

        $user = Sentinel::registerAndActivate($credentials);
        $userRole = Sentinel::findRoleByName('user');
        $userRole->users()->attach($user);

        CartController::cartToUser($user->id);

        $user_data->create([
            'user_id'   => $user->id,
            'image_id'  => 1,
            'phone'     => $request->phone,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $auth = Sentinel::authenticateAndRemember($credentials);
        if($auth){
            return redirect('/user')
                ->with('status', 'Вы успешно зарегистрированы! Добро пожаловать в личный кабинет');
        }

    }

    public function forgotten()
    {
        return view('users.forgotten');
    }

    public function reminder(Request $request, Mail $mail)
    {
        $rules = [
            'email'     =>'required|email',
        ];

        $messages = [
            'email.required'    => 'Введите email-адрес!',
            'email.email'       => 'Некорректный email-адрес!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        $user_exists = User::where('email', $request->email)->first();

        if ($user_exists) {
            $user = Sentinel::findById($user_exists->id);

            ($reminder = Reminder::exists($user)) || ($reminder = Reminder::create($user));

            Mail::send('emails.reminder', ['user' => $user, 'reminder' => $reminder], function($msg) use ($user, $request){
                $msg->from('parfumhouse@gmail.com', 'Parfum House');
                $msg->to($request->email);
                $msg->subject('Восстановление пароля');
            });

            return view('users.forgotten_email_sent');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['email' => 'Пользователь с таким email-адресом не зарегистрирован!']);
        }

    }

    public function lostpassword(Request $request)
    {
        if($request->id && $request->code){
            $user = Sentinel::findById($request->id);

            if (Reminder::exists($user, $request->code)) {
                return view('users.lostpassword');
            }
        } else {
            return redirect('/');
        }
    }

    public function changePassword(Request $request)
    {
        $rules = [
            'password'  => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ];

        $messages = [
            'password.required' => 'Введите новый пароль!',
            'password.min'      => 'Пароль должен быть не менее 6 символов!',
            'password.confirmed' => 'Введенные пароли не совпадают!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        $user = Sentinel::findById($request->id);
        Reminder::complete($user, $request->code, $request->password);

        return view('users.lostpassword_complete');
    }


    public function logout()
    {
        Sentinel::logout();
        return redirect()->back();
    }

    /**
     * Сохранение пользователей в БД как незарегистрированных
     *
     * @param Request $request
     * @return int
     */
    public function storeAsUnregistered(Request $request)
    {
        $credentials = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'password'  => 'null',
            'permissions' => [
                'unregistered' => true
            ]
        ];

        $user = Sentinel::registerAndActivate($credentials);

        $userRole = Sentinel::findRoleBySlug('unregister_user');
        $userRole->users()->attach($user);

        if ($user->id) {
            CartController::cartToUser($user->id);

            $user_data = UserData::create([
                'user_id'   => $user->id,
                'image_id'  => 1,
                'subscribe' => 0
            ]);

            $response = $user;
        } else {
            $response = 0;
        }

        return $response;
    }
}
