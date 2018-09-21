@extends('public.layouts.main')
@section('meta')
    <title>Блог</title>
    <meta name="description" content="{!! $settings->meta_description !!}">
    <meta name="keywords" content="{!! $settings->meta_keywords !!}">
@endsection

{{--@section('breadcrumbs')--}}
    {{--{!! Breadcrumbs::render('blog') !!}--}}
{{--@endsection--}}

@section('content')

    <section class="siteSection siteSection--gray siteSection--bordered full-height">
        <div class="container">
            <div class="u-text--center"><h2>Статьи</h2></div>
            <div class="row">
                @forelse($articles as $i => $article)
                    <div class="col-sm-6 col-md-3">
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
                    </div>
                @empty
                    <div class="col-sm-12">
                        Нет статей
                    </div>
                @endforelse
            </div>
            <div class="loadingItem">
                {{--<div class="u-text--center"><a href="#" class="loadingItem-link">Загрузить еще товары</a></div>--}}
                <div class="pagination--toright">
                    <div class="pagination-wrapper u-clearfix">
                        @include('public.layouts.pagination', ['paginator' => $articles])
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection