@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    HTML-страницы
@endsection
@section('content')

    <h1>Список HTML-страниц</h1>

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
                <a href="/admin/html/create" class="btn">Добавить новую</a>
            </div>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>Название</td>
                        <td>Порядок сортировки</td>
                        <td>Статус</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($content as $page)
                        <tr>
                            <td>{{ $page->name }}</td>
                            <td>{{ $page->sort_order }}</td>
                            <td class="status">
                                    <span class="{!! $page->status ? 'on' : 'off' !!}">
                                        <span class="runner"></span>
                                    </span>
                            </td>
                            <td class="actions" align="center">
                                <a class="btn btn-primary" href="/admin/html/edit/{!! $page->id !!}">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <button type="button" class="btn btn-danger" onclick="confirmHTMLDelete('{!! $page->id !!}', '{!! $page->name !!}')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" align="center">Нет добавленных HTML-страниц!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $content->links() }}
            </div>
        </div>
    </div>

    <div id="html-delete-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Подтверждение удаления</h4>
                </div>
                <div class="modal-body">
                    <p>Удалить страницу <span id="html-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <a type="button" class="btn btn-primary" id="confirm">Удалить</a>
                </div>
            </div>
        </div>
    </div>

@endsection
