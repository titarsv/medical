<div class="bottle-card" id="product-card">
    <a href="{{env('APP_URL')}}/product/{!! $product->url_alias !!}" class="bottle-card__thumb-link">
        <img src="{!! $product->image == null ? '/assets/images/no_image.jpg' : $product->image->url('product_list') !!}" alt="{!! $product->image->title !!}" class="bottle-card__thumb">
    </a>
    <a href="{{env('APP_URL')}}/product/{!! $product->url_alias !!}" class="bottle-card__name">
        {!! $product->name !!}
        <span class="bottle-card__vol">{!! $product->capacity !!} {!! $product->capacity ? $product->measures->name : '' !!}</span>
    </a>
    <div class="product-card__stars-wrap">
        <ul class="product-card__stars-list">
            @for($i=1; $i<=5; $i++)
                @break(count($product->reviews) == 0)

                @if($i <= $product->description->rating)
                    <li class="product-card__stars-item"><i class="product-card__star-icon">&#xe810;</i></li>
                @else
                    <li class="product-card__stars-item"><i class="product-card__star-icon">&#xe811;</i></li>
                @endif
            @endfor
        </ul>
        <span class="product-card__review">
            <a href="{{env('APP_URL')}}/product/{!! $product->url_alias !!}#reviews" class="product-card__review-link">{{count($product->reviews)}} отзывов</a>
        </span>
    </div>
    <input type="hidden" id="product-card__amount-input" value="1">
    <a href="javascript:void(0)"
       class="bottle-card__btn"
       id="product-card__btn"
       data-product-id="{!! $product->id !!}"
       data-user-id="{!! $user_id !!}">+ Добавить</a>
</div>