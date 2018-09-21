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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
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
                                <label class="col-sm-2 text-right">Максимальное количество слайдов</label>
                                <div class="form-element col-sm-10">
                                    <input type="text" name="quantity" class="form-control" value="{!! isset($settings->quantity) ? $settings->quantity : 6 !!}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Слайды</h4>
                    </div>
                    <div class="panel-body">
                        <div class="table slideshow-images">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="success">
                                        <th align="center" class="col-md-2">Изображение</th>
                                        <th align="center" class="col-md-2">Ссылка/Кнопка</th>
                                        <th align="center" class="col-md-2">Порядок/Статус</th>
                                        <th align="center" class="col-md-2">Заголовок/Описание</th>
                                        <th align="center" class="col-md-3">Текст</th>
                                        <th align="center" class="col-md-1">Действия</th>
                                    </tr>
                                </thead>
                                <tbody id="modules-table">
                                    @forelse($slideshow as $key => $slide)
                                        <tr>
                                            <td class="col-md-2">
                                                <input type="hidden" id="module-image-{!! $key !!}" name="slide[{!! $key !!}][image_id]" value="{!! $slide->image_id !!}" />
                                                <div id="module-image-output-{!! $key !!}" class="module-image">
                                                    <img src="/assets/images/{!! $slide->image ? $slide->image->href : '' !!}" />
                                                    <button type="button" class="btn btn-del" data-delete="{!! $key !!}" data-toggle="tooltip" data-placement="bottom" title="Удалить изображение">X</button>
                                                    <button type="button" data-open="module-image" data-key="{!! $key !!}" class="btn">Выбрать изображение</button>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div>
                                                    <b>Ссылка</b>
                                                    <input type="text" name="slide[{!! $key !!}][link]" class="form-control" value="{!! $slide->link !!}" />
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Текст кнопки</b>
                                                    <input type="text" name="slide[{!! $key !!}][button_text]" class="form-control" value="{!! json_decode($slide->slide_data)->button_text !!}" />
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Отображать ссылку</b>
                                                    <select name="slide[{!! $key !!}][enable_link]" class="form-control">
                                                        @if($slide->enable_link)
                                                            <option value="1" selected>Да</option>
                                                            <option value="0">Нет</option>
                                                        @elseif(!$slide->enable_link)
                                                            <option value="1">Да</option>
                                                            <option value="0" selected>Нет</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div>
                                                    <b>Порядок сортировки</b>
                                                    <input type="text" name="slide[{!! $key !!}][sort_order]" class="form-control" value="{!! $slide->sort_order !!}" />
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Статус</b>
                                                    <select name="slide[{!! $key !!}][status]" class="form-control">
                                                        @if($slide->status)
                                                            <option value="1" selected>Отображать</option>
                                                            <option value="0">Скрыть</option>
                                                        @elseif(!$slide->status)
                                                            <option value="1">Отображать</option>
                                                            <option value="0" selected>Скрыть</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div>
                                                    <b>Заголовок</b>
                                                    <input type="text" name="slide[{!! $key !!}][slide_title]" class="form-control" value="{!! json_decode($slide->slide_data)->slide_title !!}" />
                                                    <span style="color: red">
                                                        @if($errors->has('slide.' . $key . '.slide_title'))
                                                            {{ $errors->first('slide.' . $key . '.slide_title',':message')  }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <br>
                                                <div>
                                                    <b>Описание</b>
                                                    <input type="text" name="slide[{!! $key !!}][slide_description]" class="form-control" value="{!! json_decode($slide->slide_data)->slide_description !!}" />
                                                    <span style="color: red">
                                                        @if($errors->has('slide.' . $key . '.slide_description'))
                                                            {{ $errors->first('slide.' . $key . '.slide_description',':message')  }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="col-md-3">
                                                <div>
                                                    <b>Текст 1</b>
                                                    <input type="text" name="slide[{!! $key !!}][text][0]" class="form-control" value="{!! json_decode($slide->slide_data)->text[0] !!}" />
                                                    <span style="color: red">
                                                        @if($errors->has('slide.' . $key . '.text.0'))
                                                            {{ $errors->first('slide.' . $key . '.text.0',':message')  }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <div>
                                                    <b>Текст 2</b>
                                                    <input type="text" name="slide[{!! $key !!}][text][1]" class="form-control" value="{!! json_decode($slide->slide_data)->text[1] !!}" />
                                                    <span style="color: red">
                                                        @if($errors->has('slide.' . $key . '.text.1'))
                                                            {{ $errors->first('slide.' . $key . '.text.1',':message')  }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <div>
                                                    <b>Текст 3</b>
                                                    <input type="text" name="slide[{!! $key !!}][text][2]" class="form-control" value="{!! json_decode($slide->slide_data)->text[2] !!}" />
                                                    <span style="color: red">
                                                        @if($errors->has('slide.' . $key . '.text.2'))
                                                            {{ $errors->first('slide.' . $key . '.text.2',':message')  }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <div>
                                                    <b>Текст 4</b>
                                                    <input type="text" name="slide[{!! $key !!}][text][3]" class="form-control" value="{!! json_decode($slide->slide_data)->text[3] !!}" />
                                                    <span style="color: red">
                                                        @if($errors->has('slide.' . $key . '.text.3'))
                                                            {{ $errors->first('slide.' . $key . '.text.3',':message')  }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="col-md-1" align="center">
                                                <button class="btn btn-danger" onclick="$(this).parent().parent().remove();">Удалить</button>
                                                @if($key == count($slideshow) - 1)
                                                    <input type="hidden" value="{!! $key !!}" id="slideshow-iterator" />
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="empty">
                                            <td colspan="4" align="center">Нет добавленных слайдов!</td>
                                        </tr>
                                        <input type="hidden" value="0" id="slideshow-iterator" />
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5"></td>
                                        <td align="center"><button type="button" id="button-add-slide" class="btn">Добавить слайд</button></td>
                                    </tr>
                                </tfoot>
                            </table>
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
