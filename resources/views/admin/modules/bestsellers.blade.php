@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Модули
@endsection
@section('content')

    <h1>{!! $module->name !!}</h1>

    @if (session('message-success'))
        <div class="alert alert-success">
            {{ session('message-success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif(session('message-error'))
        <div class="alert alert-danger">
            {{ session('message-error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="form">
        <form method="post">
            {!! csrf_field() !!}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки модуля</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Статус</label>
                                <div class="form-element col-sm-10">
                                    <select name="status" class="form-control">
                                        @if($module->status)
                                            <option value="1" selected>Включить</option>
                                            <option value="0">Выключить</option>
                                        @else
                                            <option value="1">Включить</option>
                                            <option value="0" selected>Выключить</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Количество товаров для отображения</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" name="quantity" class="form-control" value="{!! $settings->quantity !!}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Товары</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Поиск</label>
                                <div class="autocomplete-search form-element col-sm-10">
                                    <input type="text"
                                           class="form-control"
                                           data-autocomplete="input-search"
                                           data-target="bestseller"
                                           placeholder="Начните вводить название товара" />
                                    <div data-output="search-results"
                                         data-target="bestseller"
                                         class="search-results"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group autocomplete">
                            <div class="row">
                                <label class="col-sm-2 text-right">Выбранные товары</label>
                                <div class="form-element col-sm-10">
                                    <div class="form-control autocomplete-selected"
                                         data-autocomplete="selected-products"
                                         data-target="bestseller">
                                        <ul>
                                            @forelse($bestsellers as $bestseller)

                                                <li>{!! $bestseller->product->name !!}
                                                    <input type="hidden" class="selected-products" name="products[]" value="{!! $bestseller->product_id !!}">
                                                    <span aria-hidden="true" onclick="$(this).parent().remove()">Удалить</span>
                                                </li>
                                            @empty
                                                <li class="empty">Нет выбранных товаров</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button type="submit" class="btn">Сохранить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        var handler = function(event){
            if($(event.target).is('li.selectable')) return;
            search_output.hide();
        };

        $(document).on('click', handler);
    </script>

@endsection
