@extends('public.layouts.main')
@section('meta')
    <title>
        {!! $category->meta_title or $category['meta_title'] !!}
        @if($paginator->currentPage() > 1) - страница {!! $paginator->currentPage() !!}@endif
    </title>
    <meta name="description" content="{!! $category->meta_description or '' !!}">
    <meta name="keywords" content="{!! $category->meta_keywords or '' !!}">
    @if(!empty($category->canonical) && empty($_GET['page']))
        <meta name="canonical" content="{!! $category->canonical !!}">
    @endif
    @if(!empty($category->robots))
        <meta name="robots" content="{!! $category->robots !!}">
    @endif
    @if($paginator->currentPage() > 1)
        <link rel="prev" href="{!! $paginator->previousPageUrl() !!}">
    @endif
    @if($paginator->currentPage() < $paginator->lastPage())
        <link rel="next" href="{!! $paginator->nextPageUrl() !!}">
    @endif
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('categories', $category) !!}
@endsection

@section('content')
    <section class="siteSection siteSection--gray">
        <div class="container">
            <h1 class="u-text--center category-title">{!! $category->name !!}</h1>
            <button type="button" class="navbar-toggle collapsed visible-sm visible-xs" data-toggle="mobile-side" data-target="#fixedmenu" aria-expanded="false">
                <span class="sr-only">фильтры</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="row">
                @if($category->name !== 'Акции')
                    <div class="col-md-3 hidden-sm hidden-xs">
                        <form action="#" method=get class="filters">
                            @if(count($price) || count($attributes))
                                <div class="filterList">
                                    <div class="tab_wrapper accordion_filter">
                                        <div class="content_wrapper">

                                            @if(count($price))
                                                <div title="tab_price" class="accordian_header tab_price active" style="display: block;">Цена<span class="arrow"></span></div>

                                                <div class="tab_content first tab_price active" style="display: block;">
                                                    <fieldset>
                                                        {{--<ul>--}}
                                                        {{--@foreach($price as $i => $attribute_value)--}}
                                                        {{--<li>--}}
                                                        {{--<input type="checkbox"--}}
                                                        {{--name="price"--}}
                                                        {{--value="{{ $i }}"--}}
                                                        {{--data-attribute="price"--}}
                                                        {{--data-value="{{ $i }}"--}}
                                                        {{--id="product-filter-price__check-{!! str_replace(['<', '>', '-'], '', $i) !!}"--}}
                                                        {{--@if($attribute_value['checked'])--}}
                                                        {{--checked--}}
                                                        {{--@endif>--}}
                                                        {{--<label for="product-filter-price__check-{!! str_replace(['<', '>', '-'], '', $i) !!}">{{ $attribute_value['name'] }}</label>--}}
                                                        {{--@if(!$attribute_value['checked'] && $attribute_value['count'])--}}
                                                        {{--<span>{{ $attribute_value['count'] }}</span>--}}
                                                        {{--@endif--}}
                                                        {{--</li>--}}
                                                        {{--@endforeach--}}
                                                        {{--</ul>--}}

                                                        <div class="price-inputs">
                                                            <div class="price-inputs__inner">
                                                                <span>от</span>
                                                                <input type="text" name="price_min" class="sliderValue val1" data-index="0" value="{{ isset($price[2]) ? $price[2] : $price[0] }}" />
                                                                <span>до</span>
                                                                <input type="text" name="price_max" class="sliderValue val2" data-index="1" value="{{ isset($price[3]) ? $price[3] : $price[1] }}" />
                                                            </div>
                                                        </div>
                                                        <div class="price-range" data-value="{{ isset($price[2]) ? $price[2] : $price[0] }};{{ isset($price[3]) ? $price[3] : $price[1] }}" data-max="{{ $price[1] }}" data-min="{{ $price[0] }}"></div>
                                                    </fieldset>
                                                </div>
                                            @endif

                                            @if(count($attributes))
                                                @foreach($attributes as $key => $attribute)
                                                    @if(count($attribute['values']))
                                                        <div title="tab_{{ $key }}" class="accordian_header tab_{{ $key }}{{ count($attribute['values']) && current($attribute['values'])['checked'] ? ' active' : '' }}" style="display: block;">{!! $attribute['name'] !!}<span class="arrow"></span></div>

                                                        <div class="tab_content first tab_{{ $key }} active" style="display: {{ count($attribute['values']) && current($attribute['values'])['checked'] ? ' block' : ' none' }};">
                                                            <fieldset>
                                                                <ul>
                                                                    @foreach($attribute['values'] as $i => $attribute_value)
                                                                        <li>
                                                                            <input type="checkbox"
                                                                                   name="filter_attributes[{!! $key !!}][value][{!! $i !!}]"
                                                                                   data-attribute="{{ $key }}"
                                                                                   data-value="{{ $i }}"
                                                                                   id="product-filter-{!! $key !!}__check-{!! $i !!}"
                                                                                   @if($attribute_value['checked'])
                                                                                   checked
                                                                                    @endif>
                                                                            <label for="product-filter-{!! $key !!}__check-{!! $i !!}">{!! htmlspecialchars($attribute_value['name']) !!}</label>
                                                                            @if(!$attribute_value['checked'] && $attribute_value['count'])
                                                                                <span>{{ $attribute_value['count'] }}</span>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </fieldset>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($root_categories && $root_categories->count())
                                <div class="filterList">
                                    <div class="filterList-category">
                                        @foreach($root_categories as $cat)
                                            <div class="filterList-category-item">
                                                <div class="filterList-category-img">
                                                    <img src="{{ $cat->image->url() }}" alt="{{ $cat->name }}">
                                                </div>
                                                <div class="filterList-category-text">
                                                    <a href="{{env('APP_URL')}}/categories/{{ $cat->url_alias }}">{{ $cat->name }}</a>
                                                </div>
                                            </div>
                                            @if($cat->hasChildren())
                                                <div class="filterList-category-accordion hidden">
                                                    <ul>
                                                        @foreach($cat->children()->where('status', 1)->get() as $children)
                                                            <li><a href="{{env('APP_URL')}}/categories/{{ $children->url_alias }}">{{ $children->name }}</a> </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                @endif
                <div class="{{ $category->name !== 'Акции' ? 'col-md-9' : 'col-md-12' }}">
                    @if($category->children->count())
                        <div class="row">
                            @foreach($category->children()->where('status', 1)->get() as $cat)
                                <div class="col-sm-6 col-md-4">
                                    <div class="categoryList-item">
                                        <div class="categoryList-img">
                                            <a href="{{env('APP_URL')}}/categories/{{ $cat->url_alias }}"><img src="{{ $cat->image->url() }}" alt="{{ $cat->name }}"></a>
                                        </div>
                                        <div class="categoryList-title">
                                            <a href="{{env('APP_URL')}}/categories/{{ $cat->url_alias }}">{{ $cat->name }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="row">
                        @forelse($products as $product)
                            <div class="col-sm-6 col-md-4">
                                @include('public.layouts.product', ['product' => $product])
                            </div>
                        @empty
                            <article>
                                <h5>В этой категории пока нет товаров!</h5>
                            </article>
                        @endforelse
                    </div>
                    <div class="loadingItem">
                        {{--<div class="u-text--center"><a href="#" class="loadingItem-link">Загрузить еще товары</a></div>--}}
                        <div class="pagination--toright">
                            <div class="pagination-wrapper u-clearfix">
                                @include('public.layouts.pagination', ['paginator' => $paginator])
                            </div>
                        </div>
                    </div>
                    @if($category->children->count()&& !empty($category->description) && $paginator->currentPage() == 1)
                        <div class="productContainer clearfix">
                            {!! $category->description !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @if($category->children->count() == 0 && !empty($category->description) && $paginator->currentPage() == 1)
        <section class="siteSection">
            <div class="container category-description">
                {!! $category->description !!}
            </div>
        </section>
    @endif
    <nav class="visible-sm visible-xs navbar-default mobile-menu-container mobile-effect" role="navigation">
        <button type="button" class="navbar-toggle collapsed visible-sm visible-xs" data-toggle="mobile-side" data-target="#fixedmenu" aria-expanded="false">
            <span class="sr-only">фильтры</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <form action="#" method=get class="filters">
            @if(count($price) || count($attributes))
                <div class="filterList">
                    <div class="tab_wrapper accordion_filter">
                        <div class="content_wrapper">
                            @if(count($price))
                                <div title="tab_price" class="accordian_header tab_price active" style="display: block;">Цена<span class="arrow"></span></div>

                                <div class="tab_content first tab_price active" style="display: block;">
                                    <fieldset>
                                        {{--<ul>--}}
                                        {{--@foreach($price as $i => $attribute_value)--}}
                                        {{--<li>--}}
                                        {{--<input type="checkbox"--}}
                                        {{--name="price"--}}
                                        {{--value="{{ $i }}"--}}
                                        {{--data-attribute="price"--}}
                                        {{--data-value="{{ $i }}"--}}
                                        {{--id="product-filterm-price__check-{!! str_replace(['<', '>', '-'], '', $i) !!}"--}}
                                        {{--@if($attribute_value['checked'])--}}
                                        {{--checked--}}
                                        {{--@endif>--}}
                                        {{--<label for="product-filterm-price__check-{!! str_replace(['<', '>', '-'], '', $i) !!}">{{ $attribute_value['name'] }}</label>--}}
                                        {{--@if(!$attribute_value['checked'] && $attribute_value['count'])--}}
                                        {{--<span>{{ $attribute_value['count'] }}</span>--}}
                                        {{--@endif--}}
                                        {{--</li>--}}
                                        {{--@endforeach--}}
                                        {{--</ul>--}}

                                        <div class="price-inputs">
                                            <div class="price-inputs__inner">
                                                <span>от</span>
                                                <input type="text" name="price_min" class="sliderValueMin val1" data-index="0" value="{{ isset($price[2]) ? $price[2] : $price[0] }}" />
                                                <span>до</span>
                                                <input type="text" name="price_max" class="sliderValueMin val2" data-index="1" value="{{ isset($price[3]) ? $price[3] : $price[1] }}" />
                                            </div>
                                        </div>
                                        <div class="price-range-min" data-value="{{ isset($price[2]) ? $price[2] : $price[0] }};{{ isset($price[3]) ? $price[3] : $price[1] }}" data-max="{{ $price[1] }}" data-min="{{ $price[0] }}"></div>
                                    </fieldset>
                                </div>
                            @endif

                            @if(count($attributes))
                                @foreach($attributes as $key => $attribute)
                                    @if(count($attribute['values']))
                                        <div title="tab_{{ $key }}" class="accordian_header tab_{{ $key }}{{ count($attribute['values']) && current($attribute['values'])['checked'] ? ' active' : '' }}" style="display: block;">{!! $attribute['name'] !!}<span class="arrow"></span></div>

                                        <div class="tab_content first tab_{{ $key }} active" style="display: {{ count($attribute['values']) && current($attribute['values'])['checked'] ? ' block' : ' none' }};">
                                            <fieldset>
                                                <ul>
                                                    @foreach($attribute['values'] as $i => $attribute_value)
                                                        <li>
                                                            <input type="checkbox"
                                                                   name="filter_attributes[{!! $key !!}][value][{!! $i !!}]"
                                                                   data-attribute="{{ $key }}"
                                                                   data-value="{{ $i }}"
                                                                   id="product-filterm-{!! $key !!}__check-{!! $i !!}"
                                                                   @if($attribute_value['checked'])
                                                                   checked
                                                                    @endif>
                                                            <label for="product-filterm-{!! $key !!}__check-{!! $i !!}">{!! htmlspecialchars($attribute_value['name']) !!}</label>
                                                            @if(!$attribute_value['checked'] && $attribute_value['count'])
                                                                <span>{{ $attribute_value['count'] }}</span>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </fieldset>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if($root_categories && $root_categories->count())
                <div class="filterList">
                    <div class="filterList-category">
                        @foreach($root_categories as $cat)
                            <div class="filterList-category-item">
                                <div class="filterList-category-img">
                                    <img src="{{ $cat->image->url() }}" alt="{{ $cat->name }}">
                                </div>
                                <div class="filterList-category-text">
                                    <a href="{{env('APP_URL')}}/categories/{{ $cat->url_alias }}">{{ $cat->name }}</a>
                                </div>
                            </div>
                            @if($cat->hasChildren())
                                <div class="filterList-category-accordion hidden">
                                    <ul>
                                        @foreach($cat->children()->where('status', 1)->get() as $children)
                                            <li><a href="{{env('APP_URL')}}/categories/{{ $children->url_alias }}">{{ $children->name }}</a> </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </form>
    </nav>
@endsection