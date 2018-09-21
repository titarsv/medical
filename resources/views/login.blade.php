@extends('public.layouts.main')

@section('meta')
    <title>Вход в личный кабинет</title>
    <meta name="description" content="{!! $settings->meta_description !!}">
    <meta name="keywords" content="{!! $settings->meta_keywords !!}">
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('login') !!}
@endsection

@section('content')

    <main id="login">
        <div class="container">
            <div class="log_reg_form">
                <div class="log_reg_form_container">
                    <nav class="log_reg_tabs">
                        {{--<ul class="log_reg_caption">--}}
                            {{--<li id="log_tab"@if(session('process')!='registration') class="active"@endif>Вход</li>--}}
                            {{--<li id="reg_tab"@if(session('process')=='registration') class="active"@endif>Регистрация</li>--}}
                        {{--</ul>--}}

                        <div class="log_reg_content
                                @if(session('process')!='registration')
                                active
                                @endif
                                " id="log_content">
                            @if(session('process')!='registration' && !empty($errors->all()))
                                <span class="error-message">
                                    {!! $errors->first() !!}
                                </span>
                            @endif
                            <form class="login_form" method="post">
                                {!! csrf_field() !!}
                                <div class="form_wrapper">
                                    <input class="form_input" type="text" name="email" placeholder="E-mail">
                                </div>
                                <div class="form_wrapper">
                                    <input class="form_input" type="password" name="password" placeholder="Пароль">
                                </div>
                                {{--<div class="form_wrapper">--}}
                                    {{--<a href="/reset_password"><span>Забыли пароль?</span></a>--}}
                                {{--</div>--}}
                                <div class="form_wrapper">
                                    <button type="submit" class="form_button">Войти</button>
                                </div>
                            </form>
                        </div>

                        {{--<div class="log_reg_content--}}
                                {{--@if(session('process')=='registration')--}}
                                {{--active--}}
                                {{--@endif--}}
                                {{--" id="reg_content">--}}
                            {{--@if(session('process')=='registration' && !empty($errors->all()))--}}
                                {{--<span class="error-message">--}}
                                    {{--{!! $errors->first() !!}--}}
                                {{--</span>--}}
                            {{--@endif--}}
                            {{--<form class="reg_form" method="post">--}}
                                {{--{!! csrf_field() !!}--}}
                                {{--<input type="hidden" name="_method" value="PUT">--}}
                                {{--<div class="form_wrapper">--}}
                                    {{--<input type="text"--}}
                                           {{--name="first_name"--}}
                                           {{--id="name"--}}
                                           {{--class="form_input @if($errors->has('first_name')) input_error @endif"--}}
                                           {{--value="{!! old('first_name') !!}" placeholder="Ваше имя">--}}
                                {{--</div>--}}
                                {{--<div class="form_wrapper">--}}
                                    {{--<input type="text"--}}
                                           {{--name="last_name"--}}
                                           {{--id="surname"--}}
                                           {{--class="form_input"--}}
                                           {{--value="{!! old('last_name') !!}"placeholder="Ваша фамилия">--}}
                                {{--</div>--}}
                                {{--<div class="form_wrapper">--}}
                                    {{--<input type="text"--}}
                                           {{--name="phone"--}}
                                           {{--id="phone"--}}
                                           {{--class="form_input @if($errors->has('phone')) input_error @endif"--}}
                                           {{--value="{!! old('phone') !!}" placeholder="Телефон">--}}
                                {{--</div>--}}
                                {{--<div class="form_wrapper">--}}
                                    {{--<input type="text"--}}
                                           {{--name="email"--}}
                                           {{--id="email"--}}
                                           {{--class="form_input @if($errors->has('email')) input_error @endif"--}}
                                           {{--value="{!! old('email') !!}" placeholder="E-mail">--}}
                                {{--</div>--}}
                                {{--<div class="form_wrapper">--}}
                                    {{--<input type="password"--}}
                                           {{--name="password"--}}
                                           {{--id="password"--}}
                                           {{--class="form_input @if($errors->has('password')) input_error @endif" placeholder="Придумайте пароль">--}}
                                {{--</div>--}}
                                {{--<div class="form_wrapper">--}}
                                    {{--<input type="password"--}}
                                           {{--name="password_confirmation"--}}
                                           {{--id="passwordr" class="form_input @if($errors->has('password_confirmation')) input_error @endif" placeholder="Подтвердите пароль">--}}
                                {{--</div>--}}
                                {{--<div class="form_wrapper">--}}
                                    {{--<button type="submit" id="reg_form_submit" class="form_button">Зарегистрироваться</button>--}}
                                {{--</div>--}}
                            {{--</form>--}}
                        {{--</div>--}}

                    </nav>
                </div>
            </div>
        </div>
    </main>

@endsection