@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Статьи
@endsection
@section('content')
    <h1>Список статей</h1>
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
                <a href="/admin/blog/create" class="btn">Добавить новую</a>
            </div>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr class="success">
                        <td>ID</td>
                        <td>Заголовок</td>
                        <td>Изображение</td>
                        <td>Alias</td>
                        <td>Опубликовано</td>
                        <td align="center">Действия</td>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>
                                <p>{{ $article->title }}</p>
                            </td>
                            <td>
                                <img class="img-thumbnail" src="/assets/images/{!! $article->image->href !!}" alt="{!! $article->image->title !!}">
                            </td>
                            <td>
                                <p>{{ $article->url_alias }}</p>
                            </td>
                            <td class="status">
                                <span class="{!! $article->published ? 'on' : 'off' !!}">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td align="center" class="actions">
                                <a class="btn btn-primary" href="/admin/blog/edit/{!! $article->id !!}">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a class="btn btn-danger" href="/admin/blog/delete/{!! $article->id !!}">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" align="center">Нет добавленных статей!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $articles->links() }}
            </div>
        </div>
    </div>


@endsection
