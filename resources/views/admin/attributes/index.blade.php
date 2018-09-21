@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Атрибуты
@endsection
@section('content')

    <h1>Список атрибутов</h1>

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
            <div class="panel-heading text-right">
                <a href="/admin/attributes/create" class="btn">Добавить новый</a>
            </div>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>Название</td>
                        <td>Значения</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($attributes as $attribute)
                        <tr>
                            <td>{{ $attribute->name }}</td>
                            <td>
                                <ul class="nav">
                                    @forelse($attribute->values as $value)
                                        <li>{!! $value->name !!}</li>
                                    @empty
                                        <li>Нет добавленных значений!</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="actions" align="center">
                                <a class="btn btn-primary" href="/admin/attributes/edit/{!! $attribute->id !!}">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger" onclick="confirmAttributesDelete('{!! $attribute->id !!}', '{{ $attribute->name }}')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" align="center">Нет добавленных атрибутов!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $attributes->links() }}
            </div>
        </div>
    </div>

    <div id="attributes-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить атрибут <span id="attribute-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

@endsection
