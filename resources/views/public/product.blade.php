@extends('public.layouts.main')
@section('meta')
    <title>
        @if(empty(trim($product->meta_title)) || $product->meta_title == 'NULL')
            {!! $product->name !!} купить в Харькове, Киеве, Украине по низкой цене
        @else
            {{ $product->meta_title }}
        @endif
    </title>

    @if(empty(trim($product->meta_description)) || $product->meta_description == 'NULL')
        <meta name="description" content="{!! !empty($product->meta_description) ? $product->meta_description : $product->name.'【Доставка по Украине 1-2 дня】【Постоянным клиентам и оптовикам скидки!】 ☎+38 (099) 235 65 13' !!}">
    @else
        <meta name="description" content="{!! $product->meta_description !!}">
    @endif

    <meta name="keywords" content="{!! $product->meta_keywords !!}">
    @if(!empty($product->robots))
        <meta name="robots" content="{!! $product->robots !!}">
    @endif

    <meta name="canonical" content="{{env('APP_URL')}}/product/{!! $product->url_alias !!}">
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('product', $product, $product->categories) !!}
@endsection

@section('content')
    <section class="siteSection siteSection--noPad siteSection--gray">
        <div class="container">
            <div class="productContainer">
                <div class="row" itemscope itemtype="http://schema.org/Product">
                    <meta itemprop="name" content="{{ $product->name }}" />
                    <link itemprop="url" href="{{env('APP_URL')}}/product/{!! $product->url_alias !!}" />
                    <link itemprop="image" href="{{ $product->image->url('full') }}" />
                    <meta itemprop="productID" content="{{ $product->id }}" />
                    <meta itemprop="price" content="{{ $product->price }}" />
                    @if(!empty($reviews))
                        @php
                            $bestRating = 0;
                            $sumRating = 0;
                            $reviewCount = 0;
                            foreach($reviews as $review){
                                if($review['parent']->grade > $bestRating){
                                    $bestRating = $review['parent']->grade;
                                }
                                $sumRating += $review['parent']->grade;
                                $reviewCount++;
                            }
                        @endphp
                        @if($reviewCount > 0)
                            <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="hidden">
                                <span itemprop="ratingValue">{{ round($sumRating/$reviewCount, 2) }}</span>
                                <span itemprop="bestRating">{{ $bestRating }}</span>
                                <span itemprop="reviewCount">{{ $reviewCount }}</span>
                            </div>
                        @endif
                    @endif

                    <div class="col-sm-12 col-md-4">
                        @if(!empty($product->old_price))
                            <div class="productList-banner productList-banner--sale" style="position: absolute; left: 50%; top: 45px; z-index: 1;">
                                Скидка {{ round(($product->old_price / $product->price - 1) * 100, 0) }}%
                            </div>
                        @endif
                        <?php $labels = $product->labels(); ?>
                        @if(!empty($product->label) && $product->label != 'z' && isset($labels[$product->label]))
                            <div class="card__img {{ $product->label }}">
                                <img src="/images/labels/{{ $product->label }}.png" alt="{{ $product->name }}">
                            </div>
                        @endif
                        <div class="productContainer-img slick-slider">
                            @forelse($gallery as $i => $image)
                                @if(is_object($image['image']))
                                    <a href="{{ $image['image']->url('full') }}" class="fancybox">
                                        <img id="image-{{ $i }}" class="gallery-image" src="{{ $image['image']->url('full') }}"{!! empty($image['alt']) ? '' : ' alt="'.$image['alt'].'"' !!}{!! empty($image['title']) ? '' : ' title="'.$image['title'].'"' !!} itemprop="image">
                                    </a>
                                @endif
                            @empty
                                <img src="/assets/images/no_image.jpg">
                            @endforelse
                        </div>
                    </div>
                    <style>
                        .zoomContainer:hover{
                            z-index: 1;
                        }
                    </style>
                    <div class="col-sm-12 col-md-8" style="z-index: 1;">
                        <div class="productContainer-title">
                            <h1>{{ $product->name }}</h1>
                            <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="hidden">
                                <span itemprop="itemreviewed">{{ $product->name }}</span>
                                <span itemprop="worstRating">0</span>
                                <span itemprop="ratingValue">{{ $product->rating }}</span>
                                <span itemprop="bestRating">5</span>
                                <span itemprop="reviewCount">{{ count($reviews) > 0 ? count($reviews) : 1 }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="productContainer-art">
                                    Артикул: <span>{{ $product->articul }}</span>
                                </div>
                                @if(count($variations))
                                    <form action="#" id="variations">
                                        <input type="hidden" id="variation" name="variation" value="">
                                        @foreach($variations as $attr_id => $attr)
                                            <div class="product-filter__wrapper">
                                                <span class="product-filter__title">{{ $attr['name'] }}:</span>
                                                <div class="product-filter__select-wrapper">
                                                    <select name="attr[{{ $attr_id }}]" class="product-filter__select variation-select">
                                                        <option value="">Сделайте выбор</option>
                                                        @php natsort($attr['values']); @endphp
                                                        @foreach($attr['values'] as $id => $name)
                                                            <option value="{{ $id }}">{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endforeach
                                        @foreach($variations_prices as $variation => $val)
                                            <input class="hidden" type="radio" id="var_{{ $variation }}" name="variation" value="{{ $val['id'] }}" data-price="{{ $val['price'] }}">
                                        @endforeach
                                    </form>
                                @endif
                                <div class="productContainer-price">
                                    @if(!empty($product->price))
                                        <div class="product-price" data-price="{{ round($product->price, 2) . ($max_price > $product->price ? ' - '.$max_price : '') }}"><span>{{ round($product->price, 2) . ($max_price > $product->price ? ' - '.$max_price : '') }}</span> грн.</div>
                                    @else
                                        <div class="product-price" data-price="{{ round($product->price, 2) . ($max_price > $product->price ? ' - '.$max_price : '') }}"><span>Цена по запросу</span></div>
                                    @endif
                                    {{--@if(!empty($product->price))--}}
                                        {{--{{ round($product->price, 2) . ($max_price > $product->price ? ' - '.$max_price : '') }} грн--}}
                                    {{--@else--}}
                                        {{--Цену уточняйте!--}}
                                    {{--@endif--}}
                                </div>
                                <div class="productContainer-exprice">
                                    @if(!empty($product->old_price))
                                        {{ round($product->old_price, 2) }} грн
                                    @endif
                                </div>
                                <div class="productContainer-quantity clearfix">
                                    <div class="productContainer-num">
                                        <div class="quantity-block">
                                            <input type="number" class="quantity-num" value="1" />
                                            <div class="quantity-arrow plus"></div>
                                            <div class="quantity-arrow minus"></div>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-primary popup-btn" data-mfp-src="#buy-popup">Купить</a>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="productContainer-info">
                                    <div class="productContainer-delivery">Доставка</div>
                                    <ul>
                                        <li>Доставка новой почтой</li>
                                        <li>Доставка любым другим перевозчиком</li>
                                        <li>Курьерская доставка</li>
                                        <li>Самовывоз (Харьков)</li>
                                    </ul>
                                </div>
                                <div class="productContainer-info">
                                    <div class="productContainer-payment">Оплата</div>
                                    <ul>
                                        <li>Оплата наличными</li>
                                        <li>Наложенный платеж</li>
                                        <li>Оплата по счету с НДС</li>
                                        <li>Оплата по счету без НДС (ФОП)</li>
                                    </ul>
                                </div>
                                @if(!empty($reviews) && isset($sumRating) && !empty($reviewCount))
                                    <div class="productContainer-info">
                                        <div style="color: #000;font-weight: 700;margin-bottom: 10px;padding-left: 40px;">Рейтинг товара</div>
                                        <div class="rating" style="margin-top: 10px;">
                                            @php
                                                $rating = round($sumRating/$reviewCount);
                                            @endphp
                                            @for($i=1; $i<=5; $i++)
                                                @if($i <= $rating)
                                                    <img src="/images/rp.png" alt="rp">
                                                @else
                                                    <img src="/images/rm.png" alt="rm">
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="productContainer-action">
                            <a href="#" class="btn popup-btn" data-mfp-src="#consult-downl-popup">Получить информацию</a>
                            {{--<a href="#" class="btn popup-btn" data-mfp-src="#doc-downl-popup">Получить документацию</a>--}}
                        </div>
                    </div>
                </div>
                <div class="tab_wrapper demo">

                    <ul class="tab_list">
                        @if(!empty($product->description))
                            <li class="active" rel="tab_1">Описание</li>
                        @endif
                        <li rel="tab_2"{{ empty($product->description) ? ' class="active"' : '' }}>Отзывы</li>
                        <!--noindex-->
                        <li rel="tab_3">Доставка</li>
                        <!--/noindex-->
                        {{--<li>Сервис</li>--}}
                    </ul>

                    <div class="content_wrapper">
                        @if(!empty($product->description))
                            <div class="tab_content active tab_1" style="display: block;">
                                {!! str_replace(array('<h1>', '</h1>'), array('<h2>', '</h2>'), $product->description) !!}
                            </div>
                        @endif

                        <div class="tab_content tab_2">
                            <div>
                                <h3>Отзывы</h3>
                                <div class="product-reviews">
                                    <div class="product-review__wrapper">
                                        @forelse($reviews as $review)
                                            <div class="product-review">
                                                <span class="product-review__title">{!! $review['parent']->user->first_name !!}</span>
                                                <p class="product-review__txt">{!! $review['parent']->review !!}</p>
                                                <small class="product-review__date">{!! $review['parent']->date !!}</small>
                                            </div>
                                            @if(!empty($review['parent']->answer))
                                                <div class="product-answer">
                                                    <i></i>
                                                    <span class="product-answer__title">Ответ</span>
                                                    <div class="product-answer__txt">{!! $review['parent']->answer !!}</div>
                                                </div>
                                            @endif
                                            {{--@if(!empty($review['comments']))--}}
                                                {{--@foreach($review['comments'] as $comment)--}}
                                                    {{--<div class="product-answer">--}}
                                                        {{--<i></i>--}}
                                                        {{--<span class="product-answer__title">Ответ</span>--}}
                                                        {{--<div class="product-answer__txt">{!! $comment->review !!}</div>--}}
                                                    {{--</div>--}}
                                                {{--@endforeach--}}
                                            {{--@else--}}
                                                {{--<form class="answer-form">--}}
                                                    {{--<h3 class="review-form__title">Оставить ответ</h3>--}}
                                                    {{--{!! csrf_field() !!}--}}
                                                    {{--<input type="hidden" name="type" value="answer">--}}
                                                    {{--<input type="hidden" name="product_id" value="{!! $product->id !!}">--}}
                                                    {{--<input type="hidden" name="parent_review_id" value="{!! $review['parent']->id !!}">--}}
                                                    {{--<div class="error-message" id="error-answer" style="display: none;">--}}
                                                        {{--<div class="error-message__text"></div>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="row">--}}
                                                        {{--<div class="col-sm-6">--}}
                                                            {{--<label for="review-form__input_name" class="review-form__label">Ваше имя</label>--}}
                                                            {{--<input type="text" id="review-form__input_name" name="name" class="review-form__input" value="{!! $user->first_name or '' !!}">--}}
                                                            {{--<label for="review-form__input_email" class="review-form__label">Ваш Email</label>--}}
                                                            {{--<input type="text" id="review-form__input_email" class="review-form__input" name="email" value="{!! $user->email or '' !!}">--}}
                                                        {{--</div>--}}
                                                        {{--<div class="col-sm-6">--}}
                                                            {{--<label for="review-form__input_comment" class="review-form__label">Комментарий</label>--}}
                                                            {{--<textarea rows="4" id="review-form__input_comment" class="review-form__input_comment review-form__input" name="review"></textarea>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="row">--}}
                                                        {{--<div class="col-sm-6 col-sm-push-6">--}}
                                                            {{--<div class="clearfix">--}}
                                                                {{--<button type="submit" class="review-form__btn">Ответить</button>--}}
                                                                {{--<a href="javascript:void(0)" class="review-form__btn review-form__btn_cancel answer-form__btn_cancel">Отмена</a>--}}
                                                            {{--</div>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                {{--</form>--}}
                                            {{--@endif--}}
                                        @empty
                                            <div class="product-review">
                                                <span class="review-item__name">У этого товара пока нет отзывов! Будьте первым!</span>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="product-review__form-wrapper">
                                    <span class="product-review__form-title">Написать отзыв - {!! $product->name !!}</span>
                                    <form class="product-review__form review-form">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="type" value="review">
                                        <input type="hidden" name="product_id" value="{!! $product->id !!}">
                                        <input class="product-review__form-input" type="text" id="review-form__input_name" name="name" value="{!! $user->first_name or '' !!}">
                                        <input class="product-review__form-input" type="text" id="review-form__input_email" name="email" value="{!! $user->email or '' !!}">
                                        <textarea class="product-review__form-textarea" placeholder="Ваш отзыв" id="review-form__input_comment" name="review"></textarea>
                                        <span class="product-review__footnote">Примечание: HTML разметка не поддерживается! Используйте обычный текст.</span>
                                        <div class="product-review__rate-wrapper">
                                            <strong class="product-review__rate-title">Оценка:</strong>
                                            <span class="product-review__rate-txt">Плохо</span>
                                            <div class="product-review__rate-checkbox">
                                                <input class="product-review__rate-chck" type="radio" name="grade" id="rating1" value="1">
                                                <label for="rating1"><span></span></label>
                                            </div>
                                            <div class="product-review__rate-checkbox">
                                                <input class="product-review__rate-chck" type="radio" name="grade" id="rating2" value="2">
                                                <label for="rating2"><span></span></label>
                                            </div>
                                            <div class="product-review__rate-checkbox">
                                                <input class="product-review__rate-chck" type="radio" name="grade" id="rating3" value="3">
                                                <label for="rating3"><span></span></label>
                                            </div>
                                            <div class="product-review__rate-checkbox">
                                                <input class="product-review__rate-chck" type="radio" name="grade" id="rating4" value="4">
                                                <label for="rating4"><span></span></label>
                                            </div>
                                            <div class="product-review__rate-checkbox">
                                                <input class="product-review__rate-chck" type="radio" name="grade" id="rating5" value="5">
                                                <label for="rating5"><span></span></label>
                                            </div>
                                            <span class="product-review__rate-txt">Хорошо</span>
                                        </div>
                                        <button type="submit" class="consult-form__btn">Отправить</button>
                                        <div class="consult-form__composition">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab_content tab_3">
                            <!--noindex-->
                            <h3>Доставка</h3>
                            <h3 class="delivery-subtitle">Компания «Завод Лабораторного Оборудования» предоставляет возможность Вам доставки любым возможным перевозчиком.</h3>
                            <span>Мы сотрудничаем со следующими компаниями-перевозчиками:</span>
                            <ul class="delivery-company">
                                <li><img src="/images/delivery/np.jpg" alt=""></li>
                                <li><img src="/images/delivery/ne.jpg" alt=""></li>
                                <li><img src="/images/delivery/it.jpg" alt=""></li>
                                <li><img src="/images/delivery/delivery.jpg" alt=""></li>
                                <li><img src="/images/delivery/al.jpg" alt=""></li>
                                <li><img src="/images/delivery/me.jpg" alt=""></li>
                            </ul>
                            <p>Для уточнения деталей и расчета стоимости отправки Вашего заказа Вы можете связаться с нашими менеджерами посредством телефонной связи: <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $settings->main_phone_1) }}">{{ $settings->main_phone_1 }}</a>/<a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $settings->main_phone_2) }}">{{ $settings->main_phone_2 }}</a> или посредством электронной почты <a href="mailto:med.oborud123@gmail.com" class="delivery-mail">med.oborud123@gmail.com</a> и в течение 10 минут Вам будет предоставлена вся необходимая информация и рекомендации. Наши специалисты с удовольствием помогут Вам подобрать наиболее оптимального перевозчика, чтобы Ваш товар прибыл в кратчайшие сроки в ближайшее к Вам отделение, чтобы Вам было удобнее его забрать.</p>
                            <p>В зависимости от выбранного типа перевозчика меняются цены доставки и сроки, и мы с радостью поделимся с Вами нашим опытом и дадим советы, как сэкономить на доставке и получить товар в максимально сжатые сроки.</p>
                            <!--/noindex-->
                        </div>

                        {{--<div class="tab_content">--}}
                        {{--<h3>Сервис</h3>--}}
                        {{--</div>--}}
                    </div>

                </div>
            </div>
        </div>
    </section>

    @if($product->sets->count())
        <section class="siteSection">
            <div class="container">
                <div class="u-text--center"><h2>Акционные комплекты</h2></div>
                <div class="setsList">
                    <div class="js-slider">
                        @foreach($product->sets as $i => $set)
                            <div class="setsList-item">
                                <div class="row">
                                    @foreach($set->set_products as $id => $set_prod)
                                        <div class="col-xs-5 col-md-3">
                                            <div class="productList-item">
                                                <div class="productList-img">
                                                    <a href="{{env('APP_URL')}}/product/{{ $set_prod->url_alias }}"><img src="{{ $set_prod->image == null ? '/assets/images/no_image.jpg' : $set_prod->image->url('product_list') }}" alt="{{ $set_prod->name }}"></a>
                                                </div>
                                                <div class="productList-title">
                                                    <a href="{{env('APP_URL')}}/product/{{ $set_prod->url_alias }}">{{ $set_prod->name }}</a>
                                                </div>
                                                <div class="productList-price">
                                                    {{ round($set_prod->price, 2) }} грн
                                                </div>
                                            </div>
                                        </div>
                                        @if($id + 1 < $set->set_products->count())
                                            <div class="col-xs-2 col-md-1">
                                                <div class="setsList-plus">
                                                    +
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div class="hidden-xs col-md-1">
                                        <div class="setsList-equal">
                                            =
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                        <div class="setsList-exprice">
                                            {{ round($set->old_price, 2) }} грн.
                                        </div>
                                        <div class="setsList-price">
                                            {{ round($set->price, 2) }} грн.
                                        </div>
                                        <div class="u-text--center"><a href="{{env('APP_URL')}}/product/{{ $set->url_alias }}" class="btn btn-secondary">Заказать комплект</a></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(!empty($product->related->count()))
        <section class="siteSection siteSection--gray">
            <div class="container">
                <div class="u-text--center"><h2>С этим товаром покупают</h2></div>
                <div class="row">
                    <div class="newsList js-slider slick-slider" data-slick='{"slidesToShow": 4, "responsive":[{"breakpoint":1199,"settings":{"slidesToShow":3}},{"breakpoint":991,"settings":{"slidesToShow":2}},{"breakpoint":576,"settings":{"slidesToShow": 1}}]}'>
                        @foreach($product->related as $related_product)
                            <div class="col-sm-6 col-md-3">
                                @include('public.layouts.product', ['product' => $related_product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- BUY POPUP -->
    <div class="mfp-hide">
        <div id="buy-popup" class="buy-popup">
            <div class="container">
                <div class="row">
                    <div class=" col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-xs-12">
                        <form class="row container-popup pbz_form clear-styles"
                              data-error-title="Ошибка отправки!"
                              data-error-message="Попробуйте отправить заявку через некоторое время."
                              data-success-title="Спасибо за заявку!"
                              data-success-message="Наш менеджер свяжется с вами в ближайшее время.">
                            <div class="col-sm-12 container-popup-text">
                                <div class="col-sm-12">
                                    <h5 class="container-popup-title">Товар для заказа</h5>
                                </div>
                                <div class="col-sm-12 buy-popup-product-wrp">
                                    <div class="col-sm-3 col-xs-12">
                                        @forelse($gallery as $image)
                                            @if(is_object($image['image']))
                                                <img src="{{ $image['image']->url('full') }}"{!! empty($image['alt']) ? '' : ' alt="'.$image['alt'].'"' !!}{!! empty($image['title']) ? '' : ' title="'.$image['title'].'"' !!} itemprop="image">
                                            @endif
                                        @empty
                                            <img src="/assets/images/no_image.jpg">
                                        @endforelse
                                    </div>
                                    <div class="col-sm-5 col-xs-12 buy-popup-text-wrp">
                                        <p class="buy-popup-text">{{ $product->name }}</p>
                                        <input type="hidden" value="{{ $product->name }}" name="title" data-title="Название товара">
                                        <p class="vendor-code">Артикул: {{ $product->articul }}</p>
                                        <input type="hidden" value="{{ $product->articul }}" name="articul" data-title="Артикул">
                                    </div>
                                    <div class="col-sm-2 col-xs-4 form-item">
                                        <input type="text" class="buy-popup-input-quant" pattern="^[ 0-9]+$" value="1" name="qty" data-title="Количество">
                                        <span class="buy-popup-text">шт</span>
                                    </div>
                                    <div class="col-sm-2 col-xs-5 form-item">
                                        <p class="buy-popup-price">
                                            @if(!empty($product->price))
                                                {{ round($product->price, 2) . ($max_price > $product->price ? ' - '.$max_price : '') }} грн
                                            @else
                                                Цену уточняйте!
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="col-sm-3 hidden-xs buy-popup-new-price-wrp">
                                        <p class="buy-popup-new-price">
                                            @if(!empty($product->price))
                                                {{ round($product->price, 2) . ($max_price > $product->price ? ' - '.$max_price : '') }} грн
                                            @else
                                                Цену уточняйте!
                                            @endif
                                        </p>
                                        <p class="buy-popup-old-price">
                                            <span style="text-decoration: line-through;">
                                                @if(!empty($product->old_price))
                                                    {{ round($product->old_price, 2) }} грн
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-5 col-sm-4 col-xs-12"></div>
                                    <div class="col-sm-2  col-xs-4 form-item">
                                        <p class="buy-popup-total-price">Итого:</p>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-xs-4 form-item">
                                        <p class="buy-popup-total-price">
                                            @if(!empty($product->price))
                                                {{ round($product->price, 2) . ($max_price > $product->price ? ' - '.$max_price : '') }} грн
                                            @else
                                                Цену уточняйте!
                                            @endif
                                        </p>
                                    </div>
                                    <!-- <div class="col-md-3"></div> -->
                                    <div class="col-sm-12 col-xs-12 buy-popup-text">
                                        Введите Ваш номер телеефона и наш менеджер поможет Вам оформить
                                    </div>
                                    <div class="col-sm-12 col-xs-12 buy-popup-btn-wrp">
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text" id="name" name="phone" class="buy-popup-tel" placeholder="Ваше номер телефона" data-title="Телефон" data-validate-required="Обязательное поле" data-validate-uaphone="Неправильный номер">
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <button type="submit" class="btn btn-secondary">Оформить заказ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button title="Close (Esc)" type="button" class="mfp-close">×</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mfp-hide">
        <div id="doc-downl-popup" class="order-popup">
            <div class="container">
                <div class="row">
                    <div class=" col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3 col-xs-12">
                        <div class="row container-popup">
                            <form class="col-md-12 container-popup-text pbz_form clear-styles"
                                  data-error-title="Ошибка отправки!"
                                  data-error-message="Попробуйте отправить заявку через некоторое время."
                                  data-success-title="Спасибо за заявку!"
                                  data-success-message="Наш менеджер свяжется с вами в ближайшее время.">
                                <div class="col-md-12">
                                    <h5 class="container-popup-title">Скачать документацию</h5>
                                </div>
                                <div class="col-md-12"><img src="/images/pop-up/documentation.jpg" alt="">
                                </div>
                                <div class="col-md-12 form-item">
                                    <label class="visually-hidden" for="ccname">Ваш номер телефона</label>
                                    <input type="text" id="ccname" name="phone" placeholder="Ваше номер телефона" data-title="Телефон" data-validate-required="Обязательное поле" data-validate-uaphone="Неправильный номер">
                                </div>
                                <div class="col-md-12"><button type="submit" class="btn btn-secondary">Скачать документацию</button></div>
                                <div class="col-md-12"><span class="container-popup-text-file">documentation.pdf</span></div>
                            </form>
                            <button title="Close (Esc)" type="button" class="mfp-close">×</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="consult-downl-popup" class="order-popup">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 col-xs-12">
                        <div class="row container-popup">
                            <div class="col-md-8 col-md-offset-2 col-xs-12">
                                <form class="col-md-12 container-popup-text pbz_form clear-styles"
                                      data-error-title="Ошибка отправки!"
                                      data-error-message="Попробуйте отправить заявку через некоторое время."
                                      data-success-title="Спасибо за заявку!"
                                      data-success-message="Наш менеджер свяжется с вами в ближайшее время.">
                                    <div class="col-md-12">
                                        <h5 class="container-popup-title">Получить консультацию</h5>
                                    </div>
                                    <div class="col-md-12"><img src="/images/pop-up/consultation.png" alt="">
                                    </div>
                                    <div class="col-md-12 form-item">
                                        <label class="visually-hidden" for="cccname">Ваш номер телефона</label>
                                        <input type="text" id="cccname" name="phone" placeholder="Ваше номер телефона" data-title="Телефон" data-validate-required="Обязательное поле" data-validate-uaphone="Неправильный номер">
                                    </div>
                                    <div class="col-md-12"><button type="submit" class="btn btn-secondary">Получить консультацию</button></div>
                                </form>
                                <button title="Close (Esc)" type="button" class="mfp-close">×</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection