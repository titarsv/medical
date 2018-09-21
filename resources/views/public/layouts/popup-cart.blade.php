<div class="popup" style="display: none" disabled>
    <article class="cart-wrap clearfix">
        <h2 id="cart-title" class="section-title">Корзина</h2>
        <form class="cart-form">
            <div class="cart-table-wrap">
                <table id="cart-table" class="cart-table">

                </table>
            </div>
            <div class="cart-result clearfix">
                <a id="cart_reload-btn" href="javascript:void(0)" class="cart-result__reload-btn">Обновить корзину</a>
                <table class="cart-result__table">
                    <tr class="cart-result__table-row">
                        <td class="cart-result__item">Сумма к оплате:</td>
                        <td id="cart-result__item" class="cart-result__item cart-result__item_right">840 грн</td>
                    </tr>
                    <tr class="cart-result__table-row">
                        <td class="cart-result__item">Доставка:</td>
                        <td class="cart-result__item cart-result__item_right">0 грн</td>
                    </tr>
                    <tr class="cart-result__table-row">
                        <td class="cart-result__item cart-result__item_accent">Итого:</td>
                        <td id="cart-result__item_accent" class="cart-result__item cart-result__item_accent cart-result__item_right">840 грн</td>
                    </tr>
                </table>
            </div>
            <div class="btn-wrap clearfix">
                <a id="from_cart_to_order_button" href="javascript:void(0)" class="cart-btn cart-btn_right cart-btn_disabled">Минимальная сумма заказа 750 грн</a>
                <a href="{{env('APP_URL')}}/" class="cart-btn cart-btn_left cart-btn_close">Продолжить покупки</a>
            </div>
        </form>
        <div class="empty-cart__content" style="display:none">
            <span class="empty-cart__text">Вы пока ничего не добавили в корзину</span>
            <img src="/img/cart.png" alt="" class="empty-cart__img">
            <a href="#" class="product__btn empty-cart_btn cart-btn_close">Продолжить покупки</a>
        </div>
        {{--<h3 class="section-title cart-wrap__slider-title">С этим часто покупают</h3>--}}
        {{--<div class="cart-slider">--}}
            {{--<div class="cart-slider-item">--}}
                {{--<img src="/img/_product_2.jpg" alt="" class="cart-slider-item__img">--}}
                {{--<span class="cart-slider-item__name">Мерный цилиндр 50мл</span>--}}
                {{--<div class="product-card__stars-wrap">--}}
                    {{--<ul class="product-card__stars-list">--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe811;</i></li>--}}
                    {{--</ul>--}}
                    {{--<span class="cart-slider-item__price">100грн</span>--}}
                {{--</div>--}}
                {{--<a href="" class="cart-slider-item__btn">+ Добавить</a>--}}
            {{--</div>--}}
            {{--<div class="cart-slider-item">--}}
                {{--<img src="/img/_product_2.jpg" alt="" class="cart-slider-item__img">--}}
                {{--<span class="cart-slider-item__name">Мерный цилиндр 50мл</span>--}}
                {{--<div class="product-card__stars-wrap">--}}
                    {{--<ul class="product-card__stars-list">--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe811;</i></li>--}}
                    {{--</ul>--}}
                    {{--<span class="cart-slider-item__price">100грн</span>--}}
                {{--</div>--}}
                {{--<a href="" class="cart-slider-item__btn">+ Добавить</a>--}}
            {{--</div>--}}
            {{--<div class="cart-slider-item">--}}
                {{--<img src="/img/_product_2.jpg" alt="" class="cart-slider-item__img">--}}
                {{--<span class="cart-slider-item__name">Мерный цилиндр 50мл</span>--}}
                {{--<div class="product-card__stars-wrap">--}}
                    {{--<ul class="product-card__stars-list">--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe811;</i></li>--}}
                    {{--</ul>--}}
                    {{--<span class="cart-slider-item__price">100грн</span>--}}
                {{--</div>--}}
                {{--<a href="" class="cart-slider-item__btn">+ Добавить</a>--}}
            {{--</div>--}}
            {{--<div class="cart-slider-item">--}}
                {{--<img src="/img/_product_2.jpg" alt="" class="cart-slider-item__img">--}}
                {{--<span class="cart-slider-item__name">Мерный цилиндр 50мл</span>--}}
                {{--<div class="product-card__stars-wrap">--}}
                    {{--<ul class="product-card__stars-list">--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>--}}
                        {{--<li class="product-card__stars-item"><i class="product-card__star-icon">&#xe811;</i></li>--}}
                    {{--</ul>--}}
                    {{--<span class="cart-slider-item__price">100грн</span>--}}
                {{--</div>--}}
                {{--<a href="" class="cart-slider-item__btn">+ Добавить</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    </article>
</div>