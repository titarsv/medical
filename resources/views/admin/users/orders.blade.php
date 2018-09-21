@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Заказы
@endsection
@section('content')

    <h1>Заказы пользователя {{ $user->email }}. <a href="/admin/users">К списку покупателей</a></h1>

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
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="table table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr class="success">
                                <td>ID</td>
                                <td>Статус</td>
                                <td>Имя пользователя</td>
                                <td>Почта пользователя</td>
                                <td>Дата заказа</td>
                                <td>Действия</td>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->status->status }}</td>
                                    <td class="description">
                                        <p>{{ isset($order->user->first_name) ? $order->user->first_name : '' }}</p>
                                    </td>
                                    <td class="description">
                                        <p>{{ isset($order->user->email) ? $order->user->email : '' }}</p>
                                    </td>
                                    <td class="description">
                                        <p>{{ $order->created_at }}</p>
                                    </td>
                                    <td class="actions">
                                        <a class="btn btn-primary" href="/admin/orders/edit/{!! $order->id !!}">
                                            <i class="glyphicon glyphicon-edit"></i>
                                        </a>
                                        {{--<a class="btn btn-info" href="/admin/users/stat/{!! $order->id !!}">--}}
                                        {{--<i class="glyphicon glyphicon-euro"></i>--}}
                                        {{--</a>--}}
                                        {{--<a class="btn btn-danger" href="/admin/users/delete/{!! $user->id !!}">--}}
                                        {{--<i class="glyphicon glyphicon-trash"></i>--}}
                                        {{--</a>--}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" align="center">Пока нет заказов!</td>
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
