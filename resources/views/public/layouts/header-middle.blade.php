<div class="main_header">
    <div class="container">
        <div class="logo_container">
            <a href="{{env('APP_URL')}}/"><img src="/img/logo.png"></a>
            <div class="descriptor">Автоаксессуары по Украине</div>
        </div>
        {!! Form::open(['route' => 'search', 'class' => 'search_block']) !!}
            {!! Form::input('search', 'text', null, ['placeholder' => 'Поиск по сайту', 'autocomplete' => 'off'] ) !!}
            <button></button>
        {!! Form::close()!!}
        <div class="phones_block">
            <a href="tel:380503236390" class="header_phone">+38(050) 323-63-90</a><br>
            <a href="tel:380733236390" class="header_phone">+38(073) 323-63-90</a><br>
            <a href="tel:380683236390" class="header_phone">+38(068) 323-63-90</a>
            <div class="callback_button" data-init="modal" data-modal="#callback-modal" data-order="Заказ обратного звонка (шапка)" data-hidden="/callback-header.html" id="0001"><span>Заказать звонок</span></div>
        </div>
        <div class="cart_block">
            @if($cart->total_quantity)
                <div id="cart_items_count">{{ $cart->total_quantity }}</div>
            @endif
            <div class="cart_btn" data-init="modal" data-modal="#cart-modal" id="cart"><img src="/img/cart_ico.png"><span>Ваша корзина</span></div>
        </div>
    </div>
</div>