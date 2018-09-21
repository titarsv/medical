@extends('public.layouts.main')
@section('meta')
    <title>Спасибо за заказ</title>
@endsection
@section('content')
    <nav class="breadrumbs">
        <div class="container">
            <ul class="breadrumbs-list">
                <li class="breadrumbs-item"><a href="http://globalprom.lh">Главная</a><i>→</i></li>
                <li class="breadrumbs-item">Оформление заказа</li>
            </ul>
        </div>
    </nav>

    <main >
        <section class="siteSection">
            <h1>Спасибо!</h1>
            <section class="subHeader" style="text-align: center; margin-bottom: 40px;">
                <div class="container">
                    <div class="succes__card">
                        <span class="succes__descr">Ваш заказ успешно оформлен</span>
                    </div>
                    <span class="succes__wait-call">Ожидайте звонок от нашего менеджера</span>
                    <a href="{{env('APP_URL')}}/" class="main-btn main-btn_accent" style="color: #1185c2;">Продолжить покупки</a>
                </div>
            </section>

            @if($latest_products && $latest_products->count() > 0)
                <section class="section-2">
                    <div class="section-title"><span>Акции</span></div>
                    <div class="container">
                        <div class="actions-slider">
                            @forelse($latest_products as $product)
                                <div class="item col-sm-4">
                                    <div class="item-inner action">
                                        <span class="item-label">Акция <i>%</i></span>
                                        <div class="item-pic__wrapper">
                                            <a href="{{env('APP_URL')}}/product/{{ $product->url_alias }}"><img class="item-pic" src="{{ $product->image == null ? '/assets/images/no_image.jpg' : $product->image->url('product_list') }}" alt=""></a>
                                        </div>
                                        <div class="item-info__wrapper">
                                            <a class="item-link" href="">{{ $product->name }}</a>
                                            @if(!empty($product->old_price))
                                                <div class="item-price-old">{{ round($product->old_price, 2) }} грн</div>
                                            @else
                                                <div class="item-price-old" style="text-decoration: none;">&nbsp;</div>
                                            @endif
                                            <div class="item-price">{{ round($product->price, 2) }} грн</div>
                                            <a class="item-btn" href="{{env('APP_URL')}}/product/{{ $product->url_alias }}">Подробнее</a>
                                        </div>
                                    </div>
                                </div>
                            @empty

                            @endforelse
                        </div>
                    </div>
                </section>
            @endif
        </section>
    </main>
@endsection