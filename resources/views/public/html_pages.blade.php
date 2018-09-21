@extends('public.layouts.main')
@section('meta')
    <title>{!! $content->meta_title or '' !!}</title>
    <meta name="description" content="{!! $content->meta_description or '' !!}">
    <meta name="keywords" content="{!! $content->meta_keywords or '' !!}">
@endsection

{{--@section('breadcrumbs')--}}
    {{--{!! Breadcrumbs::render('html', $content) !!}--}}
{{--@endsection--}}

@section('content')
    <section class="siteSection siteSection--gray siteSection--bordered">
        <div class="container">
            <div class="u-text--center"><h2>{{ $content->name }}</h2></div>
            <div class="productContainer clearfix">
                {!! html_entity_decode($content->content) !!}
            </div>
        </div>
    </section>
@endsection