<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Newpost;
use App\Models\Order;
use App\Models\Settings;
use App\Models\UserData;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\Products;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Validator;

class CheckoutController extends Controller
{
    /**
     * Создание заказа
     *
     * @param Request $request
     * @param Order $order
     * @param Cart $cart
     * @param UserData $user_data
     * @param User $users
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request, Order $order, Cart $cart, UserData $user_data, User $users)
    {
        $cart = $cart->current_cart();

        if(!$cart->total_quantity){
            return response()->json(['error' => ['cart' => 'В корзине нет товаров!']]);
        }

        $errors = $this->validateFields($request->all());
        if ($errors) {
            return response()->json(['error' => $errors]);
        }

        $rules = [
            'first_name' => 'required',
            'phone'     => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'email'     =>'required|email',
            'payment' => 'required',
            'delivery' => 'required'
        ];

        $messages = [
            'first_name.required' => 'Вы не указали имя!',
            'phone.required'    => 'Вы не указали телефон!',
            'phone.regex'       => 'Некорректный номер телефона!',
            'email.required'    => 'Вы не указали e-mail!',
            'email.email'       => 'Некорректный email-адрес!',
            'payment'          => 'Не выбран способ оплаты!',
            'delivery'          => 'Не выбран способ доставки!'
        ];

        if (isset($request->checkout_registration)) {
            if($request->checkout_registration == 'true'){
                $rules['password']  = 'required|min:6|confirmed';
                $rules['password_confirmation'] = 'min:6';

                $messages['password.required'] = 'Вы не указали пароль!';
                $messages['password.min'] = 'Пароль должен быть не менее 6 символов!';
                $messages['password.confirmed'] = 'Введенные пароли не совпадают!';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails() || is_null($cart)){
            $errors = is_null($cart) ? 'Ваша корзина пуста!' : $validator->messages();
            return response()->json(['error' => $errors]);
        }

        if (isset($request->checkout_registration)) {
            $register = new LoginController();

            if($request->checkout_registration == 'true') {
                $settings = new Setting();
                $response = $register->store($request, $user_data, $settings);
                if ($response == 'error') {
                    return response()->json(['error' => 'При регистрации произошла ошибка. Попробуйте оформить заказ без регистрации.']);
                } elseif ($response == 'email error') {
                    return response()->json(['error' => ['email' => 'Пользователь с таким e-mail адресом уже зарегистрирован!']]);
                }
            }
        }

        $user = Sentinel::check();

        if (!$user) {
            $existed_user = $users->checkIfUnregistered($request->phone, $request->email);

            if(!is_null($existed_user)) {
                $user = $existed_user;
            } else {
                $register = new LoginController();
                $user = $register->storeAsUnregistered($request);
            }
        }

        $user_name = $request->first_name;
        if ($request->last_name)
            $user_name .= ' ' . $request->last_name;

        $delivery_method = $request->delivery;
        $delivery_info = [
            'method'    => $delivery_method,
            'info'      => $request->$delivery_method
        ];

        $data = [
            'user_id'   => $user->id,
            'products'  => $cart->products,
            'total_quantity'    => $cart->total_quantity,
            'total_price'       => $cart->total_price,
            'user_info'         => json_encode([
                'name'  => $user_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]),
            'delivery'  => json_encode($delivery_info),
            'company' => $request->company,
            'nds' => $request->nds,
            'company_info' => $request->company_info,
            'payment'   => $request->payment,
            'status_id' => 0,
            'created_at' => Carbon::now()
        ];

        if ($request->cookie('current_order_id') !== null){
            $current_order_id = $request->cookie('current_order_id');
        } else {
            $current_order_id = $request->current_order_id;
        }

        if (isset($current_order_id)) {
            $current_order = $order->find($current_order_id);

            if (!is_null($current_order)) {
                $current_order->update($data);
                if($current_order->payment == 'card')
                    return $this->get_liqpay_data($current_order);
                else
                    return response()->json(['success' => 'redirect', 'order_id' => $current_order->id]);
            }
        }

        $order->fill($data)->save();
        Cookie::queue('current_order_id', $order->id);

        if($order->payment == 'card')
            return $this->get_liqpay_data($order);
        else
            return response()->json(['success' => 'redirect', 'order_id' => $order->id]);
    }

    /**
     * Подгрузка различных темплейтов в зависимости от выбранного способа доставки
     * @param Request $request
     * @param Newpost $newpost
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delivery(Request $request, Newpost $newpost)
    {
        if (!is_null($request->cookie('current_order_id'))) {
            $current_order_id = $request->cookie('current_order_id');
        } elseif ($request->order_id) {
            $current_order_id = $request->order_id;
        }else {
            $current_order_id = 0;
        }

        if ($request->delivery == 'newpost'){
            $regions = $newpost->getRegions();

            return view('public.checkout.newpost', [
                'regions'           => $regions,
                'current_order_id'  => $current_order_id
            ]);
        } else {
            return view('public.checkout.' . $request->delivery, ['current_order_id' => $current_order_id]);
        }
    }

    /**
     * Валидация полей доставки
     *
     * @param $data
     * @return mixed
     */
    public function validateFields($data)
    {
        $errors = [];

        if(isset($data['delivery'])) {
            if ($data['delivery'] == 'newpost') {
                $rules = [
                    'newpost.region' => 'not_in:0',
                    'newpost.city' => 'not_in:0',
                    'newpost.warehouse' => 'not_in:0',
                ];

                $messages = [
                    'newpost.region.not_in' => 'Выберите область!',
                    'newpost.city.not_in' => 'Выберите город!',
                    'newpost.warehouse.not_in' => 'Выберите отделение Новой Почты!',
                ];
            } elseif ($data['delivery'] == 'courier') {
                $rules = [
                    'courier.street' => 'required',
                    'courier.house' => 'required',
                ];

                $messages = [
                    'courier.street.required' => 'Не указана улица!',
                    'courier.house.required' => 'Не указан номер дома!',
                ];
            } elseif ($data['delivery'] == 'ukrpost') {
                $rules = [
                    'ukrpost.region' => 'required',
                    'ukrpost.city' => 'required',
                    'ukrpost.index' => 'required|numeric',
                    'ukrpost.street' => 'required',
                    'ukrpost.house' => 'required',
                ];

                $messages = [
                    'ukrpost.region.required' => 'Не указана область!',
                    'ukrpost.city.required' => 'Не указан город!',
                    'ukrpost.index.required' => 'Не указан почтовый индекс!',
                    'ukrpost.index.numeric' => 'Индекс должен быть числовым!',
                    'ukrpost.street.required' => 'Не указана улица!',
                    'ukrpost.house.required' => 'Не указан номер дома!',
                ];
            } elseif (!$data['delivery']) {
                $errors = [
                    'delivery' => 'Не выбран метод доставки!',
                ];
            }
        }else{
            $errors = [
                'delivery' => 'Не выбран метод доставки!',
            ];
        }

        $rules['payment'] = 'required|in:cash,prepayment';
        $messages['payment.required'] = 'Не выбран способ оплаты!';
        $messages['payment.in'] = 'Выбран некорректный способ оплаты!';

        $validator = Validator::make($data, $rules, $messages);

        if($validator->fails()){
            $errors = array_merge($errors, $validator->messages()->toArray());
        }

        if (!empty($errors))
            return $errors;

        return false;
    }

    /**
     * Завершение заказа, удаление корзины, отправка писем с информацией о заказе клиенту и
     *
     * @param Request $request
     * @param Settings $setting
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function orderComplete(Request $request, Settings $setting)
    {
        if (Cookie::has('current_order_id')) {
            $order_id = Cookie::get('current_order_id');
            Cookie::queue(Cookie::forget('current_order_id'));
        } else {
            $order_id = $request->order_id;
        }

        if(is_null($order_id)){
            return redirect('/checkout');
        }

        $user = Sentinel::check();
        $order = Order::find($order_id);

        $modules_settings = Modules::all();
        foreach ($modules_settings as $module_setting) {
            if ($module_setting->alias_name == 'latest') {
                $latest_settings = json_decode($module_setting->settings);
                $latest_status = $module_setting->status;
            } elseif ($module_setting->alias_name == 'bestsellers') {
                $bestseller_settings = json_decode($module_setting->settings);
                $bestseller_status = $module_setting->status;
            } elseif ($module_setting->alias_name == 'slideshow') {
                $slideshow_settings = json_decode($module_setting->settings);
                $slideshow_status = $module_setting->status;
            }
        }
        $latest_settings = json_decode($module_setting->settings);
//        $latest_products = Products::orderBy('created_at', 'desc')->where('stock', 1)->take($latest_settings->quantity)->get();
        $latest_products = Products::orderBy('created_at', 'desc')->where('stock', 1)->whereNotNull('action')->take(12)->get();

        if ($order->status_id){
            return view('public.thanks', ['order_id' => $order_id, 'user' => $user, 'confirmed' => true, 'latest_products' => $latest_products]);
        } else {
            $order->update(['status_id' => 1]);
        }

        $order_user = json_decode($order->user_info, true);

        $cart = new Cart();
        $cart->current_cart()->delete();

        Mail::send('emails.order', ['user' => $order_user, 'order' => $order, 'admin' => true], function($msg) use ($setting){
            $msg->from('shop@lab-oborud.com', 'Интернет-магазин lab-oborud.com');
            $msg->to(get_object_vars($setting->get_setting('notify_emails')));
            $msg->subject('Новый заказ');
        });

        Mail::send('emails.order', ['user' => $order_user, 'order' => $order, 'admin' => false], function($msg) use ($order_user){
            $msg->from('shop@lab-oborud.com', 'Интернет-магазин lab-oborud.com');
            $msg->to($order_user['email']);
            $msg->subject('Новый заказ');
        });
        
        return view('public.thanks', ['order_id' => $order_id, 'user' => $user, 'confirmed' => false, 'latest_products' => $latest_products]);

    }

    /**
     * Загрузка списка городов Новой Почты
     *
     * @param Request $request
     * @param Newpost $newpost
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request, Newpost $newpost)
    {
        $region = $newpost->getRegionRef($request->region_id);

        if (!is_null($region)) {
            $cities = $newpost->getCities($region->region_id);
        } else {
            return response()->json(['error' => 'При загрузке городов произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }

        if ($cities) {
            return response()->json(['success' => $cities]);
        } else {
            return response()->json(['error' => 'При загрузке городов произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }
    }

    /**
     * Загрузка списка отделений Новой Почты
     *
     * @param Request $request
     * @param Newpost $newpost
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWarehouses(Request $request, Newpost $newpost)
    {
        $city = $newpost->getCityRef($request->city_id);

        if (!is_null($city)) {
            $warehouses = $newpost->getWarehouses($city->city_id);
        } else {
            return response()->json(['error' => 'При загрузке отделений произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }

        if ($warehouses) {
            return response()->json(['success' => $warehouses]);
        } else {
            return response()->json(['error' => 'При загрузке отделений произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }
    }
}
