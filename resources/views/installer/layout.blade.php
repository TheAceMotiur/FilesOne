<!doctype html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='Shortcut icon' href='{{ url("/assets/installer/favicon.svg") }}'>
        <meta name="token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ url("/assets/plugin/bootstrap/bootstrap.min.css") }}">
        <link rel='preload' href='{{ url("/assets/plugin/fontawesome/css/all.min.css") }}' as='style' onload='this.onload=null;this.rel="stylesheet"'><noscript><link rel='stylesheet' href='{{ url("/assets/plugin/fontawesome/css/all.min.css") }}'></noscript>
        <link rel='preload' href='{{ url("/assets/plugin/toastify/toastify.min.css") }}' as='style' onload='this.onload=null;this.rel="stylesheet"'><noscript><link rel='stylesheet' href='{{ url("/assets/plugin/toastify/toastify.min.css") }}'></noscript>
        <link rel='preload' href='{{ url("/assets/css/main.min.css") }}' as='style' onload='this.onload=null;this.rel="stylesheet"'><noscript><link rel='stylesheet' href='{{ url("/assets/css/main.min.css") }}'></noscript>
        {!! loadFonts() !!}
    </head>
    <body class="install-page">
        <main class="install-main d-flex justify-content-center align-items-center position-relative min-vh-100">
            @yield('content')
            <div class="cursor-followers d-none d-md-block">
                <div class="cursor-follower cursor-follower-inner"></div>
                <div class="cursor-follower cursor-follower-outer"></div>
            </div>
        </main>
        <footer>
            <script src='{{ url("assets/plugin/bootstrap/bootstrap.bundle.min.js") }}' defer></script>
            <script src='{{ url("assets/plugin/toastify/toastify.min.js") }}' defer></script>
            <script src='{{ url("assets/installer/installer.js") }}' defer></script>
        </footer>
    </body>
</html>