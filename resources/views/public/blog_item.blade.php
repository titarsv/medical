@extends('public.layouts.main')
@section('meta')
    <title>{!! $article->meta_title !!}</title>
    <meta name="description" content="{!! $article->meta_description !!}">
    <meta name="keywords" content="{!! $article->meta_keywords !!}">
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('blog_item', $article) !!}
@endsection

@section('content')
    <section class="siteSection siteSection--gray">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="productContainer clearfix">
                        <h2>{!! $article->title !!}</h2>
                        <p><img src="{!! $article->image->url('article') !!}"></p>
                        <div class="articleItem-meta"><span>Статья добавлена:</span> {!! $article->date !!}</div>
                        {!! html_entity_decode($article->text) !!}
                    </div>
                    <div class="page-navs clearfix">
                        @if(is_object($prev))
                        <div class="prev">
                            <a href="{{env('APP_URL')}}/article/{!! $prev->url_alias !!}">{!! $prev->title !!}</a>
                        </div>
                        @endif
                        @if(is_object($next))
                        <div class="next">
                            <a href="{{env('APP_URL')}}/article/{!! $next->url_alias !!}">{!! $next->title !!}</a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="subscribeItem">
                        <div class="subscribeItem-title">
                            <h3>Рассылка новостей</h3>
                        </div>
                        <div class="subscribeItem-form">
                            <input type="text" id="cemail" name="cemail" placeholder="Ваш E-Mail" class="wpcf7-not-valid">
                            <button type="submit" class="btn btn-primary">Подписаться</button>
                        </div>
                    </div>
                    <div class="newsSet-item" style="margin-bottom: 0;">
                        <h3>Похожие новости</h3>
                    </div>
                    @foreach($recommended as $i => $article)
                        <div class="newsSet-item">
                            <div class="newsSet-img">
                                <a href="{{env('APP_URL')}}/article/{!! $article->url_alias !!}"><img src="{!! $article->image->url('blog_list') !!}" alt=""></a>
                            </div>
                            <div class="newsSet-title">
                                <a href="{{env('APP_URL')}}/article/{!! $article->url_alias !!}">{!! $article->title !!}</a>
                            </div>
                            <div class="newsSet-date">
                                {!! $article->date !!}
                            </div>
                            <div class="newsSet-action">
                                <a href="{{env('APP_URL')}}/article/{!! $article->url_alias !!}" class="btn">Читать подробнее</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection