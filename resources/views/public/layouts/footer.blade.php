<footer class="siteFooter">
    <nav class="siteFooter-nav clearfix">
        <div class="container">
            <div class="up-btn-wrp">
                <div class="up-btn">
                    <a href="#" class="go_to" data-destination="body">
                        <img src="/images/arrow-up.png" alt="">
                    </a>
                </div>
                <span>вверх</span>
            </div>
            <div class="siteFooter-items footer">
                <ul class="footer-nav">
                    <li class="logo-footer-wrap"><img src="/images/logo(white).png" alt="logo" srcset=""><h2 class="siteHeader-logo-title">Завод Лабораторного Оборудования</h2></li>
                    <li><a href="{{ $_SERVER['REQUEST_URI'] == '/categories/vlagomery' ? '#' : env('APP_URL').'/categories/vlagomery'}}" class="js-toggle" aria-label="Open Navigation" data-toggle=".siteHeader-catalogue"><span class="siteFooter-btn">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                            <span>Каталог товаров</span></a></li>
                    <li><a href="{{ $_SERVER['REQUEST_URI'] == '/categories/actions' ? '#' : env('APP_URL').'/categories/actions'}}">Акции</a></li>
                    {{--<li><a href="{{env('APP_URL')}}/articles">Статьи</a></li>--}}
                    <li><a href="{{ $_SERVER['REQUEST_URI'] == '/page/about' ? '#' : env('APP_URL').'/page/about'}}">О компании</a></li>
                    <li><a href="{{ $_SERVER['REQUEST_URI'] == '/page/dostavka' ? '#' : env('APP_URL').'/page/dostavka'}}">Оплата и Доставка</a></li>
                    {{--<li><a href="{{env('APP_URL')}}/page/certeficates">Дилерские сертификаты</a></li>--}}
                    <li><a href="{{ $_SERVER['REQUEST_URI'] == '/page/contacts' ? '#' : env('APP_URL').'/page/contacts'}}">Контакты</a></li>
                    <li>
                        <div class="siteFooter-numbers">
                            @if($source == 'google' || $source == 'yandex')
                                <a href="tel:+380960781597">+38 (096) 078-15-97</a>
                                <a href="tel:+380660028976">+38 (066) 002-89-76</a>
                            @else
                                <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $settings->main_phone_1) }}">{{ $settings->main_phone_1 }}</a>
                                <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $settings->main_phone_2) }}">{{ $settings->main_phone_2 }}</a>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12 copyright">
                    <p>© Завод Лабораторного Оборудования 2018 Все права защищены</p>
                </div>
            </div>
        </div>
    </nav>
</footer>

<div class="mfp-hide">
    {{--<div id='order-popup' class="order-popup">--}}
    {{--<div class="order-popup__empty">--}}
    {{--Здесь пусто...--}}
    {{--</div>--}}
    {{--<button title="Close (Esc)" type="button" class="mfp-close">×</button>--}}
    {{--</div>--}}

    {{--<div id='price-popup' class="order-popup">--}}
    {{--<strong class="popup-title">Скачать прайс</strong>--}}
    {{--<span class="popup-info">Скачайте прайс с самыми<br> актуальными ценами на <b>2.04.2018</b></span>--}}
    {{--<form action="" class="pbz_form clear-styles"--}}
    {{--data-error-title="Ошибка отправки!"--}}
    {{--data-error-message="Попробуйте отправить заявку через некоторое время."--}}
    {{--data-success-redirect="/price.pdf">--}}
    {{--<input type="tel" class="popup__input" name="phone" placeholder="Введите телефон" data-title="Телефон" data-validate-required="Обязательное поле" data-validate-uaphone="Неправильный номер">--}}
    {{--<button type="submit" class="product-order__btn btn_buy">Скачать прайс</button>--}}
    {{--</form>--}}
    {{--<img src="../../images/pdf.png" alt="pdf"/>--}}
    {{--<button title="Close (Esc)" type="button" class="mfp-close">×</button>--}}
    {{--</div>--}}

    <div id="price-downl-popup" class="order-popup">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 col-xs-12">
                    <div class="row container-popup">
                        <div class="col-md-6 col-sm-6 container-popup-img">
                            <img src="/images/pop-up/price.png" alt="Price">
                        </div>
                        <form class="col-md-6 col-sm-6 col-xs-12 container-popup-text pbz_form clear-styles"
                              data-error-title="Ошибка отправки!"
                              data-error-message="Попробуйте отправить заявку через некоторое время."
                              data-success-redirect="/assets/price.pdf">
                            <div class="col-md-12">
                                <h5 class="container-popup-title">Скачайте прайс</h5>
                                с полным списком наших товаров
                            </div>
                            <div class="col-md-12"><img src="/images/pop-up/price-download.png" alt="">
                            </div>
                            <div class="col-md-12 form-item">
                                <label class="visually-hidden" for="cname">Ваш номер телефона</label>
                                <input type="text" id="cname" name="phone" placeholder="Ваш номер телефона" data-title="Телефон" data-validate-required="Обязательное поле" data-validate-uaphone="Неправильный номер">
                            </div>
                            <div class="col-md-12"><button type="submit" class="btn btn-secondary">Скачать прайс</button></div>
                            <div class="col-md-12"><span class="container-popup-text-file">price.pdf</span></div>
                        </form>
                        <button title="Close (Esc)" type="button" class="mfp-close">×</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>