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
                    <span class="registration-form__label">
                        На Вашу почту было отправлено письмо. Для завершения процедуры восстановления пароля, пожалуйста, перейдите по ссылке, указанной в письме.
                    </span>
                </div>
            </div>
        </div>
    </section>
@endsection