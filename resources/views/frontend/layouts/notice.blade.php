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
        @include('frontend.main.header')
        @cookieconsentscripts
    </head>
    <body class="notice-page{{ setting('loader') == 1 ? ' overflow-hidden pe-none' : '' }}">
        <main class="notice-main d-flex justify-content-center align-items-center position-relative min-vh-100">
            @yield('content')
            @if (setting('loader') == 1)
                <div class="page-loader w-100 h-100">
                    <img 
                        src="{{ url("/assets/loader/".setting('loader_style').".svg") }}" 
                        class="m-auto" 
                        width="120" 
                        height="30" 
                        alt="Page loader">
                </div>
            @endif
        </main>
        <footer>
            @include('frontend.main.libraries')
            @include('frontend.main.functions')
            @include($functions)
        </footer>
        @cookieconsentview
    </body>
</html>