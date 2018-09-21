<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\Products;
use App\Models\ProductsCart;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Models\User;
use App\Models\Order;
use Crypt;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Обновление продукта в корзине
     *
     * @param Request $request
     * @param Cart $cart
     * @return mixed
     */
    public function updateCart(Request $request, Cart $cart)
    {
        $product_id = $request->product_id;

        $current_cart = $cart->current_cart();

        if(empty($request->variations)){
            $variations = [];
        }else{
            $variations = array_diff($request->variations, array(''));
        }

        if($request->action == 'add'){
            $product_quantity = $request->quantity ? $request->quantity : 1;
            $current_cart->increment_product_quantity($product_id, $product_quantity, $variations);
        }elseif($request->action == 'remove'){
            $current_cart->remove_product($product_id, $variations);
        }elseif($request->action == 'update'){
            $product_quantity = $request->quantity ? $request->quantity : 0;
            $current_cart->update_product_quantity($product_id, $product_quantity, $variations);
        }

        return $this->getCart($request);
    }

    /**
     * Корзина пользователя
     *
     * @param Request $request
     * @return $this
     */
    public function getCart(Request $request)
    {
        $cart = new Cart;
        $current_cart = $cart->current_cart();

        if(isset($request->type) && $request->type == 'order_cart'){
            return view('public.layouts.order_cart')->with('cart', $current_cart);
        }else{
            return view('public.layouts.cart')->with('cart', $current_cart);
        }
    }

    /**
     * Страница оформления заказа
     *
     * @param Cart $cart
     * @return mixed
     */
    public function show(Cart $cart)
    {
        $products = $cart->get_products();
        $current_cart = $cart->current_cart();
        $user = null;
        
        if(Sentinel::check()){
            $user = User::find(Sentinel::check()->id);
        }

        return view('public.order')
            ->with('user', $user)
            ->with('products', $products)
            ->with('cart', $current_cart)
            ->with('delivery_price', 0);
    }

    /**
     * @param Request $request
     * @return array|bool
     */
    public function update(Request $request)
    {
        $user_cart_id = Session::get('user_id');

        $current_cart = Cart::where('user_id', $user_cart_id)->first();

        if(is_null($current_cart)){
            return false;
        }
        $current_cart->products_cart()->delete();
        if(!empty($request['cart'])){
            foreach($request['cart'] as $product){
                if($product['product_quantity'] <= 0){
                    continue;
                }
                $new_products_cart = new ProductsCart($product);
                $current_cart->products_cart()->save($new_products_cart);
            }
        }

        $products_in_cart = $current_cart->products_cart()->get();
        $products_quantity = $products_in_cart->sum('product_quantity');
        $products_array = $products_in_cart->lists('product_quantity' , 'product_id')->toArray();

        $products_sum = 0;
        foreach($products_in_cart as $products){
            $products_sum += $products->product->price * $products_array[$products->product->id];
        }

        $current_cart->update(['products_quantity' => $products_quantity, 'products_sum' => $products_sum]);
//        $current_cart->update(['products_quantity' => $request['products_quantity']]);
        return ['products_quantity' => $request['products_quantity'], 'sum' => $request['sum']];
    }

    /**
     * @param $user_cart_id
     * @return mixed
     */
    public function getCartProducts($user_cart_id)
    {
        $cart = Cart::where('user_id',$user_cart_id)->first();

        return $cart;
    }

    /**
     * Метод передает корзину юзеру
     * @param $user_id
     * @return array
     */
    public static function cartToUser($user_id)
    {
        $user_cart_id = Session::get('user_id');
        $current_cart_from_sess = Cart::where('user_id', $user_cart_id)->first();
        $current_cart = [];
        if(!is_null($current_cart_from_sess)) {
            $current_cart_from_sess->update(['user_id' => $user_id]);
            $current_cart = $current_cart_from_sess;
        }
        $current_cart_from_user = Cart::where('user_id', $user_id)->first();
        if(!is_null($current_cart_from_user)) {
//            $current_cart_from_user->update(['user_id' => $user_id]);
            $current_cart = $current_cart_from_user;
            $user_cart_id = $current_cart->user_cart_id;
            Session::put('user_id', $user_cart_id);
        }

        return $current_cart;
    }
}
