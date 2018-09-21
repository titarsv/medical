@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Список заказов
@endsection
@section('content')

    <div class="content-title">
        <div class="row">
            <div class="col-sm-12">
                <h1>Список заказов</h1>
            </div>
        </div>
        @if(isset($user))
            <div class="row">
                <div class="col-sm-8"><h4>Пользователь: {!! $user->email !!}</h4></div>
                <div class="col-sm-4 text-right"><a href="/admin/users" class="btn btn-primary">Назад</a></div>
            </div>
        @endif
    </div>

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
            <div class="panel panel-default">
                <div class="table table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr class="success">
                            <td align="center">№ заказа</td>
                            <td class="left">
                                <div class="btn-group">
                                    <button type="button" id="current-cat" class="btn dropdown-toggle product-sort-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="dropdown-selected-name">Статус заказа</span>
                                        <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="/admin/orders" class="sort-buttons">Все</a>
                                        </li>
                                        @foreach($order_status as $status)
                                            <li>
                                                <a type="button"
                                                   data-sort="status"
                                                   data-value="{!! $status->id !!}"
                                                   class="sort-buttons"
                                                   onclick="filterProducts($(this))">
                                                    {!! $status->status !!}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
                            <td>Имя пользователя</td>
                            <td>Телефон пользователя</td>
                            <td>Дата заказа</td>
                            <td>Действия</td>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($orders as $order)
                            <tr>
                                <td align="center">
                                    {!! $order->id !!}
                                </td>
                                <td class="left">
                                    <span class="order-status {!! $order->class !!}"></span>
                                    <span>{!! $order->status->status or 'Незавершенный' !!}</span>
                                </td>
                                <td>
                                    {!! $order->user['name'] !!}
                                </td>
                                <td>
                                    {!! $order->user['phone'] !!}
                                </td>
                                <td>
                                    {!! $order->date !!} {!! $order->time !!}
                                </td>
                                <td class="actions">
                                    <a class="btn btn-primary" href="/admin/orders/edit/{!! $order->id !!}" data-toggle="tooltip" data-placement="top" title="Просмотр заказа">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="colspan">Пока нет заказов!</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($orders->links())
                    <div class="panel-footer text-right">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function(){
            navigateProductFilter();
        });
    </script>
@endsection

