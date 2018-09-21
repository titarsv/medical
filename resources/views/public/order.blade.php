@extends('public.layouts.main')
@section('meta')
    <title>Оформление заказа</title>
@endsection
@section('content')

    <nav class="breadrumbs">
        <div class="container">
            <ul class="breadrumbs-list">
                <li class="breadrumbs-item">
                    <a href="{{env('APP_URL')}}/">Главная</a><i>→</i>
                </li>
                <li class="breadrumbs-item">
                    Оформление заказа
                </li>
            </ul>
        </div>
    </nav>

    <main class="main-wrapper">
        <section class="order-wrapper">
            <div class="inner-page__wrapper">
                <div class="container">
                    <div class="col-xs-12"><span class="inner-page__title">Оформление заказа</span></div>
                </div>
            </div>
            <div class="container">
                <div class="col-lg-7">
                    <div class="order-page-inner" id="order_cart_content">
                        @include('public.layouts.order_cart', ['cart' => $cart, 'delivery_price' => $delivery_price])
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="order-page__forms-wrapper cart-block_checkout">
                        <span class="order-page__forms-title">Заполните необходимые поля</span>
                        <div class="error-message__text"></div>
                        <form action="" class="order-page__form order-page__form-check" id="order-checkout">
                            {!! csrf_field() !!}
                            <div class="order-page__form-input-wrapper">
                                <input type="text"
                                       placeholder="Ваше имя"
                                       name="first_name"
                                       id="name"
                                       class="@if($errors->has('first_name')) input_error @endif"
                                       value="{!! old('first_name') ? old('first_name') : ( isset($user) && $user ? $user->first_name : '' ) !!}">
                            </div>
                            <div class="order-page__form-input-wrapper">
                                <input type="text"
                                       placeholder="Ваша фамилия"
                                       name="last_name"
                                       id="surname"
                                       value="{!! old('last_name') ? old('last_name') : ( isset($user) && $user ? $user->last_name : '' ) !!}">
                            </div>
                            <div class="order-page__form-input-wrapper">
                                <input type="text"
                                       placeholder="Телефон"
                                       name="phone"
                                       id="phone"
                                       class="@if($errors->has('phone')) input_error @endif"
                                       value="{!! old('phone') ? old('phone') : ( isset($user) && $user ? $user->phone : '' ) !!}">
                            </div>
                            <div class="order-page__form-input-wrapper">
                                <input type="text"
                                       placeholder="E-mail"
                                       name="email"
                                       id="email"
                                       class="@if($errors->has('email')) input_error @endif"
                                       value="{!! old('email') ? old('email') : ( isset($user) && $user ? $user->email : '' ) !!}">
                            </div>

                            <div class="order-page__form-select-wrapper">
                                <select id="checkout-step__delivery" class="order-page__form-select" name="delivery">
                                    <option disabled="" selected="">Выберите метод доставки</option>
                                    <option value="newpost">Новая почта</option>
                                    <option value="pickup">Самовывоз</option>
                                </select>
                            </div>

                            <div id="checkout-delivery-payment"></div>
                            <textarea class="order-page__form-textarea" placeholder="Примечания к вашему заказу, например, особые пожелания отделу доставки"></textarea>
                            <button id="submit_order" class="order-page__form-btn" type="submit">Подтвердить заказ</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <div id="liqpay_checkout"></div>
    <script src="//static.liqpay.com/libjs/checkout.js" async></script>
@endsection