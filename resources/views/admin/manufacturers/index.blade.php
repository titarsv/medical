@include('admin.layouts.header')
@extends('admin.layouts.main')
@section('title')
    Линейка товаров
@endsection
@section('content')

    <h1>Список</h1>

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
                <a href="/admin/manufacturers/create" class="btn">Добавить новую</a>
            </div>
            <div class="table table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="success">
                            <td>Название</td>
                            <td align="center">Статус</td>
                            <td align="center">Действия</td>
                        </tr>
                    </thead>
                    <tbody>

                    @forelse($manufacturers as $manufacturer)
                        <tr>
                            <td>{{ $manufacturer->name }}</td>
                            <td class="status" align="center">
                                <span class="{!! $manufacturer->status ? 'on' : 'off' !!}">
                                    <span class="runner"></span>
                                </span>
                            </td>
                            <td class="actions" align="center">
                                <a class="btn btn-primary" href="/admin/manufacturers/edit/{!! $manufacturer->id !!}">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a class="btn btn-danger" href="/admin/manufacturers/delete/{!! $manufacturer->id !!}">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" align="center">Нет добавленных линеек товаров!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="panel-footer text-right">
                {{ $manufacturers->links() }}
            </div>
        </div>
    </div>

@endsection
