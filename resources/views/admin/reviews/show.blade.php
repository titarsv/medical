@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Просмотр отзыва
@endsection
@section('content')
    <div class="content-title">
        <div class="row">
            <div class="col-sm-12">
                <h1>Просмотр отзыва</h1>
            </div>
        </div>
    </div>

    <div class="form">
        <form method="post">
            {!! csrf_field() !!}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr class="success">
                                <td>Пользователь</td>
                                <td>Товар</td>
                                <td align="center">Оценка</td>
                                {{--<td align="center">Like</td>--}}
                                {{--<td align="center">Dislike</td>--}}
                                <td>Содержание отзыва</td>
                                <td>Дата и время</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td width="13%">
                                    @if(!empty($review->user))
                                        {{ $review->user->first_name }} {{ $review->user->last_name }}
                                    @else
                                        {{ $review->author }}
                                    @endif
                                </td>
                                <td width="17%">{!! $review->product->name !!}</td>
                                <td align="center" width="7%">{!! $review->grade !!}</td>
                                {{--<td align="center" width="7%">{!! count(json_decode($review->like))!!}</td>--}}
                                {{--<td align="center" width="7%">{!! count(json_decode($review->dislike))!!}</td>--}}
                                <td width="35%">{!! $review->review !!}</td>
                                <td width="14%">{!! $review->created_at !!}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4>Настройки</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Ответ</label>
                                <div class="form-element col-sm-10">
                                    <textarea name="answer" class="form-control" rows="6">{!! $review->answer !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2 text-right">Опубликовать</label>
                                <div class="form-element col-sm-10">
                                    <select name="published" class="form-control">
                                        <option value="1">Да</option>
                                        <option value="0">Нет</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-10 col-sm-push-2 text-left">
                                    <input type="hidden" name="product_id" value="{!! $review->product_id !!}">
                                    <button type="submit" class="btn">Сохранить</button>
                                    <a href="/admin/reviews" class="btn btn-primary">Назад</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
