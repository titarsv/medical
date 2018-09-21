@extends('public.layouts.main')
@section('meta')
    <title>Ошибка 404. Страница не найдена</title>
@endsection
@section('content')
    <main>
        <section class="siteSection siteSection--noPad siteSection--gray">
            <div class="container">
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="{{env('APP_URL')}}/">Главная</a></li>
                        <li>404-страница</li>
                    </ul>
                </div>
            </div>
        </section>
        <section>
            <div class="container">
                <div class="row">
                    <div class="img-wrp">
                        <img src="/images/404 NOT PAGE.png" alt="">
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection