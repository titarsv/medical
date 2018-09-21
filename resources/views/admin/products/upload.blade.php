@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Каталог товаров
@endsection
@section('content')

    <h1>Импорт товаров</h1>

    @if(session('message-error'))
        <div class="alert alert-danger">
            {{ session('message-error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="form">
        <form method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Новые товары</label>
                                <div class="form-element col-sm-10">
                                    <input type="file" name="import_file" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--<div class="panel panel-default">--}}
                    {{--<div class="panel-body">--}}
                        {{--<div class="form-group">--}}
                            {{--<div class="row">--}}
                                {{--<label class="col-sm-2 text-right">Обновлять существующие</label>--}}
                                {{--<div class="form-element col-sm-10">--}}
                                    {{--<input type="checkbox" value="1" name="update">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                @if(!empty($errors))
                    @foreach($errors as $error)
                        <p>При загрузке товара в строке {{ $error['id'] + 1 }} были зафиксированы следующие ошибки:</p>
                        <ul>
                            @foreach($error['errors'] as $error_more)
                                <li>{{ $error_more }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                @elseif(is_array($errors))
                    <h2>Все товары были успешно импортированы!</h2>
                @endif
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
@endsection
@section('before_footer')
    @include('admin.layouts.imagesloader')
@endsection
