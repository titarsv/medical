@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Запись блога
@endsection
@section('content')

    <h1>Редактирование пользователя {{ $user->title }}</h1>

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
                        <h4>Общая информация</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Имя</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="first_name" value="{!! old('first_name') ? old('first_name') : $user->first_name !!}" />
                                    @if($errors->has('first_name'))
                                        <p class="warning" role="alert">{!! $errors->first('first_name',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Фамилия</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="last_name" value="{!! old('last_name') ? old('last_name') : $user->last_name !!}" />
                                    @if($errors->has('last_name'))
                                        <p class="warning" role="alert">{!! $errors->first('last_name',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Почта</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="email" value="{!! old('email') ? old('email') : $user->email !!}" />
                                    @if($errors->has('email'))
                                        <p class="warning" role="alert">{!! $errors->first('email',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Телефон</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="phone" value="{!! old('phone') ? old('phone') : $user->user_data->phone !!}" />
                                    @if($errors->has('phone'))
                                        <p class="warning" role="alert">{!! $errors->first('phone',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Адрес</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="adress" value="{!! old('adress') ? old('adress') : $user->user_data->adress !!}" />
                                    @if($errors->has('adress'))
                                        <p class="warning" role="alert">{!! $errors->first('adress',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Компания</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="company" value="{!! old('company') ? old('company') : $user->user_data->company !!}" />
                                    @if($errors->has('company'))
                                        <p class="warning" role="alert">{!! $errors->first('company',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right control-label">Общая информация</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" class="form-control" name="other_data" value="{!! old('other_data') ? old('other_data') : $user->user_data->other_data !!}" />
                                    @if($errors->has('other_data'))
                                        <p class="warning" role="alert">{!! $errors->first('other_data',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Изображение</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Выберите изображение </label>
                                <div class="form-element col-sm-10">
                                    {{--<input type="hidden" id="image" name="image_id" value="{!! old('image_id') ? old('image_id') : 1 !!}" />--}}
                                    <input type="hidden" id="image" name="image_id" value="{!! old('image_id') ? old('image_id') : $user->user_data->image_id !!}" />
                                    <div id="image-output" class="category-image">
                                        {{--<img src="/assets/images/{!! old('href') ? old('href') : 'no_image.jpg' !!}" />--}}
                                        <img src="/assets/images/{!! old('href') ? old('href') : $user->user_data->image->href !!}" />
                                        <button type="button" class="btn btn-del" data-toggle="tooltip" data-placement="bottom" title="Удалить изображение">X</button>
                                        <button type="button" data-open="image" id="add-image" class="btn">Выбрать изображение</button>
                                    </div>
                                    @if($errors->has('image_id'))
                                        <p class="warning" role="alert">{!! $errors->first('image_id', ':message') !!}</p>
                                    @endif
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
@endsection
@section('before_footer')
    @include('admin.layouts.imagesloader')
@endsection
