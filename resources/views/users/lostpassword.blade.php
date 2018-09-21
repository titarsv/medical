@extends('public.layouts.main')

@section('meta')
    <title>Восстановление пароля</title>
    <meta name="description" content="{!! $settings->meta_description !!}">
    <meta name="keywords" content="{!! $settings->meta_keywords !!}">
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('forgotten') !!}
@endsection

@section('content')
    <section>
        <div class="container">
            <div class="title-wrap">
                <h2 class="section-title">Восстановление пароля</h2>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-push-3 col-md-8 col-md-push-2 col-sm-10 col-sm-push-1">
                    @if(!empty($errors->all()))
                        <div class="error-message">
                            <div class="error-message__text">
                                {!! $errors->first() !!}
                            </div>
                        </div>
                    @endif

                    <form class="registration-form" method="post">
                        {!! csrf_field() !!}
                        <div class="clearfix">
                            <label class="registration-form__label" for="password">Введите новый пароль*</label>
                            <input type="password" name="password" id="password" class="registration-form__input @if($errors->has('email')) input-error @endif">
                        </div>
                        <div class="clearfix">
                            <label class="registration-form__label" for="password_confirmation">Повторите пароль*</label>
                            <input type="password_confirmation" name="password_confirmation" id="password_confirmation" class="registration-form__input @if($errors->has('password_confirmation')) input-error @endif">
                        </div>
                        <button type="submit" class="registration-form__btn">Продолжить</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection