<!doctype html>
<html 
    lang="{{ LaravelLocalization::getCurrentLocale() }}" 
    dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}" 
    @if (setting('default_color_mode') == 1)
        {!! session('color') == 'dark' ? 'class="dark-mode"' : '' !!}>
    @else
        {!! session('color') == 'light' ? '' : 'class="dark-mode"' !!}>
    @endif
    <head>
        @include('admin.main.header')
    </head>
    <body>
        <div class="dashboard d-flex position-relative">
            <div class="dashboard-sidebar{{ session('sidebar-collapsed') == 1 ? ' sm' : '' }}">
                @include('admin.main.sidebar')
            </div>
            <div class="dashboard-content{{ session('sidebar-collapsed') == 1 ? ' lg' : ''}}">
                @include('admin.main.topbar')
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </div>
        <footer>
            @include('admin.main.footer')
            @include('admin.main.libraries')
            @include('admin.main.functions')
            @include($functions)
        </footer>
    </body>
</html>