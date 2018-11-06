@extends('public.layouts.main')
@section('meta')
    <title>{!! $settings->meta_title !!}</title>
    <meta name="description" content="{!! $settings->meta_description !!}">
    <meta name="keywords" content="{!! $settings->meta_keywords !!}">
@endsection

@section('content')

    <section class="siteSection siteSection--noPad">
        <div class="siteTeaser">
            <div class="container">
                <div class="row">
                    <div class="newsList js-slider slick-slider" data-slick='{"slidesToShow": 1, "autoplay": true, "autoplaySpeed": 5000}'>
                        @foreach($slideshow as $slide)
                            <div class="col-md-8">
                                <div class="siteTeaser-wrp">
                                    <div>
                                        <div class="siteTeaser-title">{!! json_decode($slide->slide_data)->slide_title !!}</div>
                                        <div class="siteTeaser-text">{!! json_decode($slide->slide_data)->slide_description !!}</div>
                                        @if($slide->enable_link)
                                            <a class="btn btn-secondary btn--big" href="{!! $slide->link !!}">{!! json_decode($slide->slide_data)->button_text !!}</a>
                                        @else
                                            <a href="#" class="btn btn-secondary btn--big" data-mfp-src="{!! $slide->link !!}">{!! json_decode($slide->slide_data)->button_text !!}</a>
                                        @endif
                                    </div>
                                    <img src="{!! $slide->image->url() !!}" alt="" style="object-fit: contain; object-position: center center; min-width: 330px;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--<section class="siteSection siteSection--noPad">--}}
        {{--<div class="siteTeaser">--}}
            {{--<div class="container">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-md-8">--}}
                        {{--<div class="siteTeaser-title">Lorem ipsum dolor</div>--}}
                        {{--<div class="siteTeaser-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eget ultrices sem. Sed ultricies pellentesque nulla, eget aliquet lacus eleifend ac. Vivamus semper, elit quis suscipit cursus, urna dolor tincidunt erat, a consequat est ex ut orci. Quisque sed risus pharetra, aliquam magna a, iaculis magna. Fusce sagittis urna.</div>--}}
                        {{--<a href="#" class="btn btn-secondary btn--big">Читать далее</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}

    @if(count($actions))
        <section class="siteSection siteSection--gray-light">
            <div class="container">
                <h2 class="u-text--center">Акции</h2>
                <div class="row">
                    <div class="newsList js-slider slick-slider" data-slick='{"slidesToShow": 4, "responsive":[{"breakpoint":1199,"settings":{"slidesToShow":3}},{"breakpoint":991,"settings":{"slidesToShow":2}},{"breakpoint":576,"settings":{"slidesToShow": 1}}]}'>
                        @foreach($actions as $product)
                            <div class="col-sm-6 col-md-3">
                                @include('public.layouts.product', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    @if(count($new_products))
    <section class="siteSection siteSection--gray-light">
        <div class="container">
            <h2 class="u-text--center">Новинки</h2>
            <div class="row">
                <div class="newsList js-slider slick-slider" data-slick='{"slidesToShow": 4, "responsive":[{"breakpoint":1199,"settings":{"slidesToShow":3}},{"breakpoint":991,"settings":{"slidesToShow":2}},{"breakpoint":576,"settings":{"slidesToShow": 1}}]}'>
                    @foreach($new_products as $product)
                        <div class="col-sm-6 col-md-3">
                            @include('public.layouts.product', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif
    {!! $settings->about !!}
    {{--<section class="siteSection">--}}
        {{--<div class="container">--}}
            {{--<h2 class="u-text--center">Сотрудничество с нами</h2>--}}
            {{--<div class="row">--}}
                {{--<div class="col-sm-6 col-md-3">--}}
                    {{--<div class="cooperationList-item">--}}
                        {{--<div class="cooperationList-img">--}}
                            {{--<a href="#"><img src="/images/colab-img-1.jpg" alt=""></a>--}}
                        {{--</div>--}}
                        {{--<div class="cooperationList-text">--}}
                            {{--<h3>Самый широкий ассортимент более 1500 разновидностей лабораторного оборудования</h3>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-sm-6 col-md-3">--}}
                    {{--<div class="cooperationList-item">--}}
                        {{--<div class="cooperationList-img">--}}
                            {{--<a href="#"><img src="/images/colab-img-2.jpg" alt=""></a>--}}
                        {{--</div>--}}
                        {{--<div class="cooperationList-text">--}}
                            {{--<h3>На весь продаваемый товар вы получаете гарантию и сертификаты качества</h3>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-sm-6 col-md-3">--}}
                    {{--<div class="cooperationList-item">--}}
                        {{--<div class="cooperationList-img">--}}
                            {{--<a href="#"><img src="/images/colad-img-3.png" alt=""></a>--}}
                        {{--</div>--}}
                        {{--<div class="cooperationList-text">--}}
                            {{--<h3>Менеджеры с медицинским образованием помогут подобрать идеальное решение</h3>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-sm-6 col-md-3">--}}
                    {{--<div class="cooperationList-item">--}}
                        {{--<div class="cooperationList-img">--}}
                            {{--<a href="#"><img src="/images/colad-img-4.png" alt=""></a>--}}
                        {{--</div>--}}
                        {{--<div class="cooperationList-text">--}}
                            {{--<h3>Возврат товара в случае брака за счет фирмы</h3>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}
    {{--<section class="siteSection siteSection--bordered benefitSection">--}}
        {{--<div class="container">--}}
            {{--<h2 class="u-text--center">Наши Преимущества</h2>--}}
            {{--<div class="row">--}}
                {{--<div class="col-sm-6 col-md-6 col-lg-5 col-lg-offset-1">--}}
                    {{--<div class="benefitsList">--}}
                        {{--<ul>--}}
                            {{--<li><span><img src="/images/benef-1.jpg" alt=""></span>Товар всегда в наличии на складе</li>--}}
                            {{--<li><span><img src="/images/benef-2.jpg" alt=""></span>Регулярные скидки и акции</li>--}}
                            {{--<li><span><img src="/images/benef-3.jpg" alt=""></span>Cрок службы не менее 5 лет</li>--}}
                            {{--<li><span><img src="/images/benef-4.jpg" alt=""></span>Отгрузка 24 часа</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-sm-6 col-md-6 col-lg-5 ">--}}
                    {{--<div class="benefitsList">--}}
                        {{--<ul>--}}
                            {{--<li><span><img src="/images/benef-5.jpg" alt=""></span>Европейское качество продукции</li>--}}
                            {{--<li><span><img src="/images/benef-6.jpg" alt=""></span>Качество товаров подтверджено сертификатами</li>--}}
                            {{--<li><span><img src="/images/benef-7.jpg" alt=""></span>Регулярная метрологическая аттестация на измерительную технику</li>--}}
                            {{--<li><span><img src="/images/benef-8.jpg" alt=""></span>Оплата наложенным платежом после проверки качества товара на месте</li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}
    {{--<section class="siteSection siteSection--gray-light">--}}
        {{--<div class="container">--}}
            {{--<h1 class="inform-section-title">Магазин медицинского оборудования</h1>--}}
            {{--<h3 class="u-text--center inform-title">Ваш универсальный магазин медицинского оборудования.<br/> «Качество, Которому Можно Доверять»</h3>--}}
            {{--<img src="/images/about-us.png" class="alignleft" alt="About Us">--}}
            {{--<p>Один из самых надежных поставщиков медицинского оборудования для лабораторий, у нас есть знания, опыт и ответственность, которые вы ищете при покупке товаров и материалов для здравоохранения.</p>--}}
            {{--<p>Наши опытные сотрудники будут работать с Вами во время всего процесса: от заказа до получения товара.--}}
                {{--Помните, мы обеспечиваем надежное, качественные товары от ведущих изготовлений и даем гарантию на всю продукцию, поэтому вы можете доверять нашей работе.--}}
            {{--</p>--}}
            {{--<p class="inform-text">Если у вас есть вопросы, вы можете связаться с нами онлайн или позвонить нам по телефону +38 (099) 235 65 13 или +38 (098) 640 77 30.</p>--}}
            {{--<div class="u-text--center">--}}
                {{--<a href="{{env('APP_URL')}}/page/about" class="btn btn-third btn--big">Читать далее</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}
    {{--<section class="siteSection siteSection--gray">--}}
        {{--<h2 class="u-text--center">Статьи</h2>--}}
        {{--<div class="newsList js-slider slick-slider" data-slick='{centerMode: true,variableWidth: true,centerPadding: "60px",slidesToShow: 1}'>--}}
            {{--@foreach($articles as $i => $article)--}}
                {{--<div class="newsList-item">--}}
                    {{--<div class="newsList-img">--}}
                        {{--<a href="/articles/{!! $article->url_alias !!}"><img src="{!! $article->image->url('blog_list') !!}" alt=""></a>--}}
                    {{--</div>--}}
                    {{--<div class="newsList-title">--}}
                        {{--<a href="/articles/{!! $article->url_alias !!}">{!! $article->title !!}</a>--}}
                    {{--</div>--}}
                    {{--<div class="newsList-text">--}}
                        {{--{!! $article->subtitle !!}--}}
                    {{--</div>--}}
                    {{--<div class="newsList-action">--}}
                        {{--<a href="/articles/{!! $article->url_alias !!}" class="btn btn-secondary btn--big">Перейти</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--@endforeach--}}
        {{--</div>--}}
    {{--</section>--}}
@endsection