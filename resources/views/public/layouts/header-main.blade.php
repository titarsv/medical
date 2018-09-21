<header class="siteHeader">
    <div class="siteHeader-info">
        <div class="container">
            <div class="row siteHeader-info-line">
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <div class="siteHeader-logo">
                        @if(request()->route() && request()->route()->getName() == 'home')
                            <img src="/images/logo(color).png" alt="Завод Лабораторного Оборудования">
                            <h2 class="siteHeader-logo-title">Завод Лабораторного Оборудования</h2>
                        @else
                            <a href="{{env('APP_URL')}}/"><img src="/images/logo(color).png" alt="Завод Лабораторного Оборудования"></a>
                            <a href="{{env('APP_URL')}}/"><h2 class="siteHeader-logo-title">Завод Лабораторного Оборудования</h2></a>
                        @endif
                    </div>
                </div>
                <div class="hidden-xs col-sm-6 col-md-7">
                    <div class="siteHeader-search">
                        {!! Form::open(['route' => 'search', 'class' => 'form-inline', 'method' => 'get']) !!}
                        <div class="search-inner">
                            {{--{!! Form::input('search', 'text', null, ['placeholder' => 'Поиск ...', 'class' => 'form-control', 'id' => 'exampleInputName2'] ) !!}--}}
                            <div class="autocomplete-search form-element">
                                <input type="text"
                                       name="text"
                                       class="form-control"
                                       data-autocomplete="input-search"
                                       placeholder="Поиск ..."
                                       id="exampleInputName2"/>
                                <div data-output="search-results"
                                     class="search-results" style="display: none"></div>
                            </div>
                            <button type="submit" class="btn btn-third">Искать</button>
                        </div>
                        {!! Form::close()!!}
                    </div>
                </div>
                <div class="col-xs-6 col-sm-2 col-md-2">
                    <div class="siteHeader-numbers">
                        <p><img src="/images/call.png" alt="" class="phone-shake">Позвоните нам</p>
                        @if($source == 'google' || $source == 'yandex')
                            <a href="tel:+380960781597">+38 (096) 078-15-97</a>
                            <a href="tel:+380660028976">+38 (066) 002-89-76</a>
                        @else
                            <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $settings->main_phone_1) }}">{{ $settings->main_phone_1 }}</a>
                            <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $settings->main_phone_2) }}">{{ $settings->main_phone_2 }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container visible-xs" style="background-color: #f4f7fa; padding-bottom: 5px; position: relative; z-index: 1;">
        <div class="row">
            <div class="col-xs-12">
                <div class="siteHeader-search">
                    {!! Form::open(['route' => 'search', 'class' => 'form-inline', 'method' => 'get']) !!}
                    <div class="search-inner">
                        {{--{!! Form::input('search', 'text', null, ['placeholder' => 'Поиск ...', 'class' => 'form-control', 'id' => 'exampleInputName2'] ) !!}--}}
                        <div class="autocomplete-search form-element">
                            <input type="text"
                                   name="text"
                                   class="form-control"
                                   data-autocomplete="input-search"
                                   placeholder="Поиск ..." />
                            <div data-output="search-results"
                                 class="search-results" style="display: none"></div>
                        </div>
                        <button type="submit" class="btn btn-third">Искать</button>
                    </div>
                    {!! Form::close()!!}
                </div>
            </div>
        </div>
    </div>
    <nav class="siteHeader-nav clearfix">
        <div class="container siteHeader-nav-wrp">
            <div class="siteHeader-items">
                <div class="siteHeader-logo no-display">
                    <a href="{{env('APP_URL')}}/"><img src="/images/logo(color).png" alt="Завод Лабораторного Оборудования"></a>
                    <a href="{{env('APP_URL')}}/"><h2 class="siteHeader-logo-title">Завод Лабораторного Оборудования</h2></a>
                </div>
                <div class="burger-menu-wrp">
                    <a class="burger-menu no-display js-toggle" href="#" data-toggle=".siteHeader-nav"></a>
                    <a href="#" class="siteHeader-nav-mobButton js-toggle btn btn-primary menu-btn" aria-label="Open Navigation" data-toggle=".siteHeader-nav">
                        Меню
                    </a>
                </div>
                <a href="#" class="btn btn-secondary popup-btn no-display price-downl" data-mfp-src="#price-downl-popup">Скачать прайс</a>
                <ul>
                    <li>
                        <a href="{{ $_SERVER['REQUEST_URI'] == '/categories/vlagomery' ? '#' : env('APP_URL').'/categories/vlagomery'}}" class="js-toggle visible-md visible-lg" aria-label="Open Navigation" data-toggle=".siteHeader-catalogue">
                            <span class="siteHeader-btn">
                                <span class="burger-menu"></span>
                                <span class="burger-menu"></span>
                                <span class="burger-menu"></span>
                            </span>
                            <span>Каталог товаров</span>
                        </a>
                        <a href="#" class="js-toggle hidden-md hidden-lg" aria-label="Open Navigation" data-toggle=".siteHeader-catalogue">
                            <span class="siteHeader-btn">
                                <span class="burger-menu"></span>
                                <span class="burger-menu"></span>
                                <span class="burger-menu"></span>
                            </span>
                            <span>Каталог товаров</span>
                        </a>
                    </li>
                    <li><a href="{{ $_SERVER['REQUEST_URI'] == '/categories/actions' ? '#' : env('APP_URL').'/categories/actions'}}">Акции</a></li>
                    {{--<li><a href="{{env('APP_URL')}}/articles">Статьи</a></li>--}}
                    <li><a href="{{ $_SERVER['REQUEST_URI'] == '/page/about' ? '#' : env('APP_URL').'/page/about'}}">О компании</a></li>
                    <li><a href="{{ $_SERVER['REQUEST_URI'] == '/page/dostavka' ? '#' : env('APP_URL').'/page/dostavka'}}">Оплата и Доставка</a></li>
                    {{--<li><a href="{{env('APP_URL')}}/page/certeficates">Дилерские сертификаты</a></li>--}}
                    <li><a href="{{ $_SERVER['REQUEST_URI'] == '/page/contacts' ? '#' : env('APP_URL').'/page/contacts'}}">Контакты</a></li>
                    <li><a href=""></a></li>
                    <li><a href=""></a></li>
                    <li><a href=""></a></li>
                    <li><a href=""></a></li>
                    <li><a href="#" class="btn btn-secondary popup-btn" data-mfp-src="#price-downl-popup">Скачать прайс</a></li>
                </ul>
                <div class="siteHeader-numbers no-display">
                    <p><img src="/images/call.png" alt="" class="phone-shake"> Позвоните нам</p>
                    <div>
                        @if($source == 'google' || $source == 'yandex')
                            <a href="tel:+380960781597">+38 (096) 078-15-97</a>
                            <a href="tel:+380660028976">+38 (066) 002-89-76</a>
                        @else
                            <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $settings->main_phone_1) }}">{{ $settings->main_phone_1 }}</a>
                            <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $settings->main_phone_2) }}">{{ $settings->main_phone_2 }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @include('public.layouts.main-menu')
</header>