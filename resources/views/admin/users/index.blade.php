@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Пользователи
@endsection
@section('content')

    <h1>{{ $title }}</h1>

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

    <div class="panel-group">
        <div class="panel panel-default">
            {{--<div class="panel-heading text-right">--}}
                {{--<a href="/admin/users/create" class="btn">Добавить</a>--}}
            {{--</div>--}}
            <div class="panel-group">
                <div class="panel panel-default">
                    {{--<div class="panel-heading text-right">--}}
                        {{--<a href="/admin/blog/create" class="btn">Добавить новую</a>--}}
                    {{--</div>--}}
                    <div class="table table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr class="success">
                                <td>ID</td>
                                <td>Имя</td>
                                <td>Почта</td>
                                <td>Заказы</td>
                                <td>Отзывы</td>
                                <td>Список желаний</td>
                                <td>Зарегистрирован</td>
                                <td>Редактировать</td>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td class="description">
                                        <p>{{ $user->first_name }}</p>
                                    </td>
                                    <td class="description">
                                        <p>{{ $user->email }}</p>
                                    </td>
                                    <td class="description">
                                        <p>
                                            <a class="btn btn-info" href="/admin/users/stat/{!! $user->id !!}">
                                                {{ is_array($user->orders) ? count($user->orders) : 0 }} <i class="glyphicon glyphicon-usd"></i>
                                            </a>
                                        </p>
                                    </td>
                                    <td class="description">
                                        <p>
                                            <a class="btn btn-info" href="/admin/users/reviews/{!! $user->id !!}">
                                                {{ is_array($user->review) ? count($user->reviews) : 0 }} <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                        </p>
                                    </td>
                                    <td class="description">
                                        <p>
                                            <a class="btn btn-info" href="/admin/users/wishlist/{!! $user->id !!}">
                                                {{ is_array($user->wishlist) ? count($user->wishlist) : 0 }} <i class="glyphicon glyphicon-heart"></i>
                                            </a>
                                        </p>
                                    </td>
                                    <td class="description">
                                        {{--<p>qqq</p>--}}
                                        <p>{{ $user->roles[0]->slug == 'user' ? 'Да' : 'Нет'}}</p>
                                    </td>
                                    <td class="actions">
                                        <a class="btn btn-primary" href="/admin/users/edit/{!! $user->id !!}">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>

                                        {{--<a class="btn btn-danger" href="/admin/users/delete/{!! $user->id !!}">--}}
                                            {{--<i class="glyphicon glyphicon-trash"></i>--}}
                                        {{--</a>--}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" align="center">Пока нет покупателей!</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
