@extends('public.layouts.main')
@section('meta')
    <title>Поиск: {{ $search_text }}</title>
    <meta name="description" content="Поиск по запросу: {{ $search_text }}">
    <meta name="keywords" content="{{ $search_text }}">
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('search') !!}
@endsection

@section('content')
    <main class="main-wrapper">
        <section class="siteSection">
            <div class="container">
                <h1>Поиск: {{ $search_text }}</h1>
                <div class="col-md-12">
                    <div class="row">
                        @forelse($products as $product)
                            <div class="col-sm-6 col-md-4">
                                @include('public.layouts.product', ['product' => $product])
                            </div>
                        @empty
                            <article>
                                <h5>Нет таких товаров!</h5>
                            </article>
                        @endforelse
                    </div>
                    <div class="loadingItem">
                        <div class="pagination--toright">
                            <div class="pagination-wrapper u-clearfix">
                                @include('public.layouts.pagination', ['paginator' => $paginator])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection