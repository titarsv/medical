<!DOCTYPE html>
<html>
@include('public.layouts.header')

<body class="{{ Request::path()=='/' ? ' home' : '' }}">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXG38R3"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="siteContent">
    @include('public.layouts.header-main')
    <main>
    @yield('breadcrumbs')
    @yield('content')
    </main>
</div>
@include('public.layouts.footer')
@include('public.layouts.footer-scripts')
</body>
</html>