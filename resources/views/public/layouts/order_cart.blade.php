@if(!is_null($cart))
    <div class="order-page__title-wrapper">
        <strong class="order-page__title">В корзине: </strong>
        <span class="order-page__count-items">{{ $cart->total_quantity or '0' }} {{ Lang::choice('товар|товара|товаров', $cart->total_quantity, [], 'ru') }}</span>
    </div>
    <ul class="order-page__list">
        @foreach ($cart->get_products() as $code => $product)
            @if(is_object($product['product']))
                <li class="order-page__item">
                    <div class="order-page__pic-wrapper">
                        <img class="order-page__pic" src="{{ is_null($product['product']->image) ? '/assets/images/no_image.jpg' : $product['product']->image->url('cart') }}" alt="">
                    </div>
                    <a href="{{env('APP_URL')}}/product/{{ $product['product']->url_alias }}" class="order-page__title-item">
                        {{ $product['product']->name }}
                        @if(!empty($product['variations']))
                            (
                            @foreach($product['variations'] as $variation)
                                @foreach($product['product']->attributes as $attr)
                                    @if($attr->attribute_value_id == $variation)
                                        @if($attr->value)
                                        {{ $attr->value->name }}
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                            )
                        @endif
                    </a>
                    <div class="order-page__count-wrapper">
                        <div class="order-page__minus cart_minus">–</div>
                        <input class="order-page__count-field count_field" type="text" pattern="^[ 0-9]+$" value="{{ $product['quantity'] }}" size="5" data-prod-id="{{ $code }}">
                        <div class="order-page__plus cart_plus">+</div>
                    </div>
                    <span class="order-page__price" data-one-price="{{ round($product['price'], 2) }}">{{ number_format( round($product['price'], 2), 0, ',', ' ' ) }} грн.</span>
                    <i class="order-page__del mc_item_delete" data-prod-id="{{ $code }}"></i>
                </li>
            @endif
        @endforeach
    </ul>
    <div class="order-page__totals">
        <a class="order-page__back" href="#" onclick="window.history.back();">Вернуться в каталог</a>
        <div>
            <span class="order-page__totals-title">Общая стоимость: </span>
            <strong class="order-page__sum">{{ $cart->total_price ? number_format( round($cart->total_price, 2), 0, ',', ' ' ) : '0' }} грн.</strong>
        </div>
    </div>
@else
    <div class="order-page__empty">
        Здесь пусто...
    </div>
@endif