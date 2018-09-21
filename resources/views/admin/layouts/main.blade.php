<body>
<div class="container-fluid">
    <nav class="navbar navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                    <span class="navbar-brand">
                        @yield('title')
                    </span>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <p class="navbar-text">{!! $user->first_name !!} {!! $user->last_name !!}</p>
                    {{--@if (session('user'))--}}
                        {{--<p class="navbar-text">{!! session('user')->first_name !!} {!! session('user')->last_name !!}</p>--}}
                    {{--@endif--}}
                </li>
                <li>
                    <a href="/admin/orders" data-toggle="tooltip" data-placement="bottom" title="Новые заказы">
                        <span class="glyphicon glyphicon-edit"></span>
                        <span class="badge">{{ $orders }}</span>
                    </a>
                </li>
                <li>
                    <a href="/" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Перейти в магазин">
                        <span class="glyphicon glyphicon-new-window"></span>
                    </a>
                </li>
                <li>
                    <a href="/logout" data-toggle="tooltip" data-placement="bottom" title="Выйти">
                        <span class="glyphicon glyphicon-log-out"></span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <main>
        <div class="container-fluid">
            <div class="row">
                <aside id="sidebar" class="col-md-2 col-sm-3 col-xs-4">
                    @include('admin.layouts.sidebar')
                </aside>
                <div class="clear col-md-2 col-sm-3 col-xs-4"></div>
                <div id="content" class="col-md-10 col-sm-9 col-xs-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</div>
@yield('before_footer')
@include('admin.layouts.footer')