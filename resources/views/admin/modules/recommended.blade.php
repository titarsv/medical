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
                            <div class="table">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="success">
                                            <td>Категория</td>
                                            <td>Ввод названия товара</td>
                                            <td align="center">Выбранные товары</td>
                                        </tr>
                                    </thead>
                                    <tbody id="recommended-products">
                                        @forelse($categories as $category)
                                            <tr>
                                                <td>
                                                    {!! $category->name !!}
                                                </td>
                                                <td>
                                                    <div class="autocomplete-search form-element">
                                                        <input type="text"
                                                               class="form-control"
                                                               data-autocomplete="input-search"
                                                               data-target="recommended-{!! $category->id !!}"
                                                               placeholder="Начните вводить название товара" />
                                                        <div data-output="search-results"
                                                             data-target="recommended-{!! $category->id !!}"
                                                             data-manufacturer="{!! $category->id !!}"
                                                             class="search-results"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group autocomplete">
                                                        <div class="form-element">
                                                            <div class="form-control autocomplete-selected"
                                                                 data-autocomplete="selected-products"
                                                                 data-target="recommended-{!! $category->id !!}">
                                                                <ul>
                                                                    @if(!empty($recommended[$category->id]))
                                                                    @foreach($recommended[$category->id] as $product)
                                                                        <li>
                                                                            {!! $product['name'] !!}
                                                                            <input type="hidden"
                                                                                   class="selected-products"
                                                                                   name="products[{!! $category->id !!}][]"
                                                                                   data-manufacturer="{!! $category->id !!}"
                                                                                   value="{!! $product['id'] !!}">
                                                                            <span aria-hidden="true" onclick="$(this).parent().remove()">Удалить</span>
                                                                        </li>
                                                                        @endforeach
                                                                    @else
                                                                        <li class="empty">Нет выбранных товаров</li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Данный модуль работает в связке с категориями товара! Сначала Вам следует добавить категории!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
