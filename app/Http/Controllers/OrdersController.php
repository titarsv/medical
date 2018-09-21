<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\Order;
use App\Models\Products;
use App\Models\Modules;
use App\Models\OrderStatus;
use App\Models\ProductsInOrder;
use App\Models\User;
use App\Models\UserData;
use App\Models\Cart;
use App\Models\ProductsCart;
use Carbon\Carbon;
use Cartalyst\Sentinel\Native\Facades\Sentinel;

use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{

    public function index(Request $request)
    {
        if (isset($request->status)) {
            $orders = Order::where('status_id', $request->status)->orderBy('id', 'desc')->paginate(20);
        } elseif (isset($request->weeks)) {
            $date = Carbon::now()->subWeek(2);
            $orders = Order::where('created_at', '>', $date)->orderBy('id', 'desc')->paginate(20);
        } else {
            $orders = Order::orderBy('id', 'desc')->paginate(20);
        }

        foreach ($orders as $order) {
            $order->user = json_decode($order->user_info, true);
            $order->date = $order->created_at->format('d.m.Y');
            $order->time = $order->created_at->format('H:i');
            if ($order->status_id) {
                if ($order->status_id == 1){
                    $order->class = 'warning';
                } else {
                    $order->class = 'info';
                }
            } else {
                $order->class = 'danger';
            }
        }
        return view('admin.orders.index', [
            'orders' => $orders,
            'order_status' => OrderStatus::all()
        ]);
    }

    public function edit($id)
    {
        $order = Order::find($id);

        $order->user = json_decode($order->user_info);
        $order->date = $order->updated_at->format('d.m.Y');
        $order->time = $order->updated_at->format('H:i');
        if ($order->status_id) {
            if ($order->status_id == 1){
                $order->class = 'warning';
            } else {
                $order->class = 'info';
            }
        } else {
            $order->class = 'danger';
        }

        return view('admin.orders.edit', [
            'order' => $order,
            'orders_statuses' => OrderStatus::all()
        ]);
    }

//    public function show(Cart $cart)
//    {
//        $products = $cart->get_products();
//
//        if(Sentinel::check()){
//            $adress = User::find(Sentinel::check()->id)->user_data->adress;
//
//            return view('public.order')
//                ->with('adress', json_decode($adress))
//                ->with('products', $products);
//        }
//        return view('public.order')
//            ->with('products', $products);
//    }

    public function update($id, Request $request)
    {
        $status_id = $request->status;
        $order = Order::find($id)->update([
            'status_id' => $status_id
        ]);
//        return dd($order);
        $orders = Order::all();
        return redirect('/admin/orders')
            ->with('message-success', 'Заказ № ' . $id . ' успешно обновлен.');
    }

    public function newOrderUser(Request $request)
    {
        $user_id = Sentinel::check()->id;
        $rules = [
            'first_name'            => 'required',
            'phone'                 => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'email'                 => 'required|email'
        ];
        $messages = [
            'first_name.required'    => 'Не заполнены обязательные поля!',
            'phone.required'         => 'Не заполнены обязательные поля!',
            'phone.regex'            => 'Некорректный телефон!',
            'email.required'         => 'Не заполнены обязательные поля!',
            'email.email'            => 'Некорректный email-адрес!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }
        User::find($user_id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name
        ]);

        $adress = json_encode([
            'city' => $request->city,
            'street' => $request->street,
            'house' => $request->house,
            'flat' => $request->flat
        ]);
        UserData::where('user_id', $user_id)->update([
            'phone'     => $request->phone,
            'adress' => $adress,
            'other_data' => json_encode($request->except(['_token', 'first_name', 'last_name', 'city', 'street', 'house', 'flat'])),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $order_data = json_encode($request->except(['_token', 'first_name', 'last_name', 'email', 'phone', 'password', 'password_confirmation']));

        $this->orderStore($user_id, $order_data);
        return redirect('/user/history')
            ->with('status', 'Ваш заказ оформлен.');
    }
    public function newOrder(Request $request)
    {
//        return dd($request);
        $password = $request->password ? $request->password : 'null';
        $order_data = json_encode($request->except(['_token', 'first_name', 'last_name', 'email', 'phone', 'password', 'password_confirmation']));
        $rules = [
            'first_name'            => 'required',
            'phone'                 => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'email'                 => 'required|email'
        ];
        $messages = [
            'first_name.required'    => 'Не заполнены обязательные поля!',
            'phone.required'         => 'Не заполнены обязательные поля!',
            'phone.regex'            => 'Некорректный телефон!',
            'email.required'         => 'Не заполнены обязательные поля!',
            'email.email'            => 'Некорректный email-адрес!',
        ];

        $user_exists = User::where('email', $request->email)->first();

        if($request->registration == 'on'){
            $rules['email'] = 'required|email|unique:users';
            if ($user_exists){
                $user = Sentinel::findById($user_exists->id);
                if($user->inRole('unregister_user')){
                    $rules['email'] = 'required|email';
                }
            }

            $rules['password'] = 'required|min:6|confirmed';
            $rules['password_confirmation'] = 'required|min:6';

            $messages['password.required'] = 'Не заполнены обязательные поля!';
            $messages['password.min'] = 'Пароль должен быть не менее 6 символов!';
            $messages['password.confirmed'] = 'Введенные пароли не совпадают!';
            $messages['email.unique'] = 'Пользователь с таким email-ом уже зарегистрирован123!';
        }else{
            //$request->merge(['password' => $password]);
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        if ($user_exists){
            $user = Sentinel::findById($user_exists->id);
            $user_id = $user->id;

            if($user->inRole('unregister_user')){
                if($request->registration == 'off'){
                    $this->orderStore($user_id, $order_data);        // записываем в базу и радуемся
                    return redirect('/thank_you')
                        ->with('order_status', 'Ваш заказ оформлен. Оператор свяжется с вами в ближайшее время.');
                }
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


                $this->orderStore($user_id, $order_data);        // записываем в базу заказ и радуемся

                $data = UserData::where('user_id', $user_id)->first();
                $data->phone = $request->phone;
                $data->save();

                $credentials['email'] = $request->email;
                $auth = Sentinel::authenticateAndRemember($credentials);

                if($auth){
                    return redirect('/user/history')
                        ->with('status', 'Ваш заказ оформлен. Оператор свяжется с вами в ближайшее время.');
                }else{
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['error' => 'При регистрации произошла ошибка!']);
                }

            }else{
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['email' => 'Пользователь с таким email-ом уже зарегистрирован!']);
            }
        }

        //return dd($request);
        if($request->registration == 'off'){
            $user_role = Sentinel::findRoleBySlug('unregister_user');
            $credentials['permissions'] = ['unregistered' => true];
        }else{
            $user_role = Sentinel::findRoleBySlug('user');
            $credentials['permissions'] = ['user' => true];
        }
        $credentials = [
            'email' => $request->email,
            'password' => $password,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'permissions' => [
                'user' => true
            ]
        ];


        $new_user = Sentinel::registerAndActivate($credentials);

        $user_role->users()->attach($new_user);
        $user_id = $new_user->id;

        $this->orderStore($user_id, $order_data);        // записываем в базу и радуемся заказу

        UserData::create([
            'user_id'   => $user_id,
            'image_id'  => 1,
            'phone'     => $request->phone,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        if($request->registration == 'on') {
            $auth = Sentinel::authenticateAndRemember($credentials);
            if ($auth) {
                return redirect('/user/history')
                    ->with('status', 'Ваш заказ оформлен. Оператор свяжется с вами в ближайшее время.');
            }
        }else{
            return redirect('/thank_you')
                ->with('order_status', 'Ваш заказ оформлен. Оператор свяжется с вами в ближайшее время.');
        }
    }

    /**
     * @param $user_id
     * @param $order_data
     * @return bool
     */
    public function orderStore($user_id, $order_data)
    {
        $user_cart_id = Session::get('user_id');

        $current_cart = Cart::where('user_id', $user_cart_id)->first();

        $new_order_data = [
            'user_id' => $user_id,
            'products_sum' => $current_cart->products_sum,
            'products_quantity' => $current_cart->products_quantity,
            'status_id' => 1,
            'order_data' => $order_data
        ];
        $new_order = Order::create($new_order_data);
        foreach($current_cart->products_cart as $products){
            $products_in_cart['product_id'] = $products->product->id;
            $products_in_cart['product_quantity'] = $products->product_quantity;
            $products_in_cart['product_sum'] = $products->product_quantity * $products->product->price;

            $new_products_cart = new ProductsInOrder($products_in_cart);
            $new_order->products()->save($new_products_cart);
        }
        $current_cart->delete();
        $new_user_cart_id = md5(rand(0,100500));
        Session::put('user_id', $new_user_cart_id);
        return true;
    }

    public function thank_you()
    {
        $modules_settings = Modules::all();

        foreach ($modules_settings as $module_setting) {
            if ($module_setting->alias_name == 'latest') {
                $latest_settings = json_decode($module_setting->settings);
            }
        }
//        $latest_products = Products::orderBy('created_at', 'desc')->take($latest_settings->quantity)->get();
        $latest_products = Products::orderBy('created_at', 'desc')->where('stock', 1)->whereNotNull('action')->take(12)->get();
        return view('public.thanks')->with('latest_products', $latest_products);
    }
}
