@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Просмотр заказа № {{ $order->id }}
@endsection
@section('content')

    <div class="content-title">
        <div class="row">
            <div class="col-sm-12">
                <h1>Просмотр заказа № {{ $order->id }}</h1>
            </div>
        </div>
    </div>

    <div class="panel-group">
        <div class="panel panel-default">
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td align="center">Артикул</td>
                        <td>Изображение</td>
                        <td>Наименование</td>
                        <td>Количество</td>
                        <td align="center">Цена</td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->getProducts() as $item)
                        <tr>
                            <td align="center">{!! $item['product']->articul !!}</td>
                            <td><img src="{!! $item['product']->image->url() !!}" class="img-thumbnail"></td>
                            <td>
                                {!! $item['product']->name !!}
                                @if(!empty($item['variations']))
                                    (
                                    @foreach($item['variations'] as $variation)
                                        @foreach($item['product']->attributes as $attr)
                                            @if($attr->attribute_value_id == $variation)
                                                {{ $attr->value->name }}
                                            @endif
                                        @endforeach
                                    @endforeach
                                    )
                                @endif
                            </td>
                            <td>{!! $item['quantity'] !!} шт</td>
                            <td align="center">{!! round($item['price'] * $item['quantity']*$rate, 2) !!} грн</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" class="right">Итого:</td>
                        <td>{!! $order->total_quantity !!} шт</td>
                        <td align="center">{!! round($order->total_price*$rate, 2) !!} грн</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Информация о заказе</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="table table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td colspan="2" class="colspan">
                                        Покупатель
                                    </td>
                                </tr>
                                </thead>
                                <tr>
                                    <td>Покупатель</td>
                                    <td>{!! $order->user->name !!}</td>
                                </tr>
                                <tr>
                                    <td>Телефон</td>
                                    <td>{!! $order->user->phone !!}</td>
                                </tr>
                                <tr>
                                    <td>Почта</td>
                                    <td>{!! $order->user->email !!}</td>
                                </tr>
                                <tr>
                                    <td>Заказы покупателя</td>
                                    <td><a href="/admin/users/stat/{!! $order->user_id !!}" >Все заказы</a></td>
                                </tr>
                                <tr>
                                    <td>Сумма заказа</td>
                                    <td>{!! round($order->total_price*$rate, 2) !!} грн</td>
                                </tr>
                                <tr>
                                    <td>Количество товаров</td>
                                    <td>{!! $order->total_quantity !!}</td>
                                </tr>
                                <tr>
                                    <td>Дата заказа</td>
                                    <td>{!! $order->date !!} {!! $order->time !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="table table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <td colspan="2" class="colspan">
                                        Доставка и оплата
                                    </td>
                                </tr>
                                </thead>
                                @foreach($order->getDeliveryInfo() as $key => $value)
                                    @if($key == 'method')
                                        <tr>
                                            <td>Способ доставки</td>
                                            <td>{!! $value !!}</td>
                                        </tr>
                                    @endif
                                    @if($key == 'region')
                                        <tr>
                                            <td>Область</td>
                                            <td>{!! $value !!}</td>
                                        </tr>
                                    @endif
                                    @if($key == 'city')
                                        <tr>
                                            <td>Город</td>
                                            <td>{!! $value !!}</td>
                                        </tr>
                                    @endif
                                    @if($key == 'warehouse')
                                        <tr>
                                            <td>Отделение Новой Почты</td>
                                            <td>{!! $value !!}</td>
                                        </tr>
                                    @endif
                                    @if($key == 'index')
                                        <tr>
                                            <td>Почтовый индекс</td>
                                            <td>{!! $value !!}</td>
                                        </tr>
                                    @endif
                                    @if($key == 'street')
                                        <tr>
                                            <td>Улица</td>
                                            <td>{!! $value !!}</td>
                                        </tr>
                                    @endif
                                    @if($key == 'house')
                                        <tr>
                                            <td>Дом</td>
                                            <td>{!! $value !!}</td>
                                        </tr>
                                    @endif
                                    @if($key == 'apart')
                                        <tr>
                                            <td>Квартира</td>
                                            <td>{!! $value !!}</td>
                                        </tr>
                                    @endif
                                    @if($key == 'error')
                                        <tr>
                                            <td colspan="2" class="colspan">{!! $value !!}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td>Способ оплаты</td>
                                    <td>
                                        @if($order->payment == 'cash')
                                            Наличными при самовывозе
                                        @elseif($order->payment == 'prepayment')
                                            Предоплата
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Настройки</h4>
            </div>
            <div class="panel-body">
                <form action="/admin/orders/edit/{!! $order->id !!}" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <div class="row">
                            <label class="col-sm-2 text-right control-label">Статус заказа</label>
                            <div class="form-element col-sm-10">
                                <select name="status" class="form-control">
                                    @foreach($orders_statuses as $status)
                                        @if($status->id == $order->status_id)
                                            <option value="{{ $status->id }}" selected>{{ $status->status }}</option>
                                        @else
                                            <option value="{{ $status->id }}">{{ $status->status }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-sm-push-2 text-left">
                                <button type="submit" class="btn">Сохранить</button>
                                <a href="/admin/orders" class="btn btn-primary">Назад</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
