@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Атрибуты
@endsection
@section('content')

    <h1>Добавление атрибута</h1>

    @if(session('message-error'))
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
                                <label class="col-sm-2 text-right control-label">Название</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" data-translit="input" class="form-control" name="name" value="{!! old('name') !!}" />
                                    @if($errors->has('name'))
                                        <p class="warning" role="alert">{!! $errors->first('name',':message') !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Параметры</label>
                                <div class="form-element col-sm-10">
                                    <div class="row form-group">
                                        <div class="col-xs-6 attribute-name">
                                            <input name="unit" class="form-control" value="{{ old('unit') ? old('unit') : '' }}" type="text" placeholder="Единица измерения">
                                        </div>
                                        <div class="col-xs-6 attribute-name">
                                            <select name="filter_type" class="form-control">
                                                @foreach($filter_type as $type => $name)
                                                    <option value="{!! $type !!}">{!! $name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($errors->has('values'))
                            <p class="warning" role="alert">{!! $errors->first('values',':message') !!}</p>
                        @endif
                        <div class="form-group attribute-value">
                            <div class="row">
                                <label class="col-sm-2 text-right">Значения</label>
                                <div class="form-element col-sm-10">
                                    @if(old('values'))
                                        @foreach(old('values') as $id => $value)
                                            @foreach($value as $new_id => $new_attribute)
                                                <div class="row form-group">
                                                    <div class="col-xs-5 attribute-name">
                                                        <input type="text"
                                                               name="values[new][{!! $new_id !!}][name]"
                                                               class="form-control"
                                                               value="{!! $new_attribute['name'] !!}"
                                                        />
                                                        @if($errors->has('values.new.' . $new_id . '.name'))
                                                            <p class="warning" role="alert">{!! $errors->first('values.new.' . $new_id . '.name',':message') !!}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-xs-5 attribute-name">
                                                        <input type="text"
                                                               name="values[new][{!! $new_id !!}][value]"
                                                               class="form-control"
                                                               value="{!! $new_attribute['value'] !!}"
                                                        />
                                                        @if($errors->has('values.new.' . $new_id . '.name'))
                                                            <p class="warning" role="alert">{!! $errors->first('values.new.' . $new_id . '.value',':message') !!}</p>
                                                        @endif
                                                    </div>
                                                    <div class="col-xs-2 text-center">
                                                        <button type="button" class="btn btn-danger" onclick="$(this).parent().parent().remove();">
                                                            <i class="glyphicon glyphicon-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                        <input type="hidden" value="{!! $new_id ? $new_id : 0 !!}" id="attribute-values-iterator" />
                                    @else
                                        <input type="hidden" value="0" id="attribute-values-iterator" />
                                    @endif
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn" id="button-add-attribute">Добавить</button>
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
