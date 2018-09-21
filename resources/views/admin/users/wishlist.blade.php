@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Список желаний
@endsection
@section('content')

    <h1>Список желаний пользователя {{ $user->email }}. <a href="/admin/users">К списку покупателей</a></h1>

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
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td align="center">Товар</td>
                        <td align="center">Категория товара</td>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($wishlist as $product)
                        <tr>
                            <td align="center">{{ $product->product->name }}</td>
                            <td align="center">{!! $product->product->category->name !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Пользователь не добавил ни одного отзыва</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="panel-footer text-right">
                {!! $wishlist->links() !!}
            </div>
        </div>
    </div>

    <div id="reviews-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить отзыв?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

@endsection
