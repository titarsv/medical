<div class="productList-item">
    @if(!empty($product->old_price))
        <div class="productList-banner productList-banner--sale">
            Скидка {{ round(($product->old_price / $product->price - 1) * 100, 0) }}%
        </div>
    @endif
    <?php $labels = $product->labels(); ?>
    @if(!empty($product->label) && $product->label != 'z' && isset($labels[$product->label]))
        <a href="{{env('APP_URL')}}/product/{{ $product->url_alias }}" class="card__img {{ $product->label }}">
            <img src="/images/labels/{{ $product->label }}.png" alt="{{ $product->name }}">
        </a>
    @endif
    <div class="productList-img">
        <a href="{{env('APP_URL')}}/product/{{ $product->url_alias }}"><img src="{{ $product->image == null ? '/assets/images/no_image.jpg' : $product->image->url('product_list') }}" alt="{{ $product->name }}"></a>
    </div>
    <div class="productList-title">
        <a href="{{env('APP_URL')}}/product/{{ $product->url_alias }}">{{ $product->name }}</a>
    </div>
    <div class="productList-price">
        @if(!empty($product->old_price))
            <span>{{ round($product->old_price, 2) }} грн</span>
        @endif
        @if(!empty($product->price))
            {{ round($product->price, 2) }} грн
        @else
            Цену уточняйте!
        @endif
    </div>
</div>