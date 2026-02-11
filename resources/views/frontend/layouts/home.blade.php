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
    <body class="home-page{{ setting('loader') == 1 ? ' overflow-hidden pe-none' : '' }}">
        <div class="nav-container sticky-top">
            @include('frontend.main.navigation')
        </div>
        <main>
            @yield('content')
            @if (setting('loader') == 1)
                <div class="page-loader w-100 h-100">
                    <img 
                        src="{{ url("/assets/loader/".setting('loader_style').".svg") }}" 
                        class="m-auto" 
                        width="100" 
                        height="100" 
                        alt="Page loader">
                </div>
            @endif
        </main>
        <footer>
            @include('frontend.main.footer')
            @include('frontend.main.libraries')
            @include('frontend.main.functions')
            @include($functions)
        </footer>
        @cookieconsentview
    </body>
</html>