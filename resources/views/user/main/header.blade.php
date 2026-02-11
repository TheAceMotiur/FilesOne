<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ ($pageName ?? '') . ' | ' . __('lang.dashboard') . ' | ' . setting('name') }}</title>
<meta name='description' content='{{ setting('description') }}'>
<meta name='keywords' content='{{ setting('keywords') }}'>
<meta name='robots' content='noindex, nofollow'>
<link rel='canonical' href='{{ url()->current() }}'>
<link rel='Shortcut icon' href='{{ setting('favicon') ? img('other', setting('favicon')) : '' }}'>
<meta name="token" content="{{ csrf_token() }}">
@if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl')
    {!! library('/assets/plugin/bootstrap/bootstrap.rtl.min.css', true) !!}
    {!! library('/assets/css/dashboard.rtl.min.css', true) !!}
@else
    {!! library('/assets/plugin/bootstrap/bootstrap.min.css', true) !!}
    {!! library('/assets/css/dashboard.min.css', true) !!}
@endif
{!! library('/assets/plugin/toastify/toastify.min.css') !!}
{!! library('/assets/plugin/gridjs/gridjs.min.css') !!}
{!! library('/assets/plugin/fontawesome/css/all.min.css') !!}
{!! library('/assets/plugin/apexcharts/apexcharts.min.css') !!}
{!! library('/assets/plugin/overlayscrollbars/overlayscrollbars.min.css') !!}
{!! library('assets/plugin/dropzone/dropzone.min.css') !!}
{!! loadFonts() !!}
@if (setting('recaptcha_status'))
    {!! RecaptchaV3::initJs() !!}
@endif
@if (setting('analytics_measurement') && Cookies::hasConsentFor('analytics'))
    <script 
        async 
        src="https://www.googletagmanager.com/gtag/js?id={{ setting('analytics_measurement') }}">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ setting('analytics_measurement') }}');
    </script>
@endif
<script>
    var cssVars = {
        "color1": getComputedStyle(document.documentElement).getPropertyValue('--color-1'),
        "color2": getComputedStyle(document.documentElement).getPropertyValue('--color-2'),
        "color3": getComputedStyle(document.documentElement).getPropertyValue('--color-3'),
        "color4": getComputedStyle(document.documentElement).getPropertyValue('--color-4'),
        "color5": getComputedStyle(document.documentElement).getPropertyValue('--color-5'),
        "color6": getComputedStyle(document.documentElement).getPropertyValue('--color-6'),
    };
    var langVars = {
        "locale": "{{ LaravelLocalization::getCurrentLocale() }}",
        "monthsShort": [
            '{{ __('lang.jan') }}', 
            '{{ __('lang.feb') }}', 
            '{{ __('lang.mar') }}', 
            '{{ __('lang.apr') }}', 
            '{{ __('lang.may') }}', 
            '{{ __('lang.jun') }}', 
            '{{ __('lang.jul') }}', 
            '{{ __('lang.aug') }}', 
            '{{ __('lang.sep') }}', 
            '{{ __('lang.oct') }}', 
            '{{ __('lang.nov') }}', 
            '{{ __('lang.dec') }}'
        ],
        "file_quota": "{{ __('lang.file_quota') }}",
        "file_types": "{{ __('lang.file_types') }}",
        "visitor_analytics": "{{ __('lang.visitor_analytics') }}",
        "free_space": "{{ __('lang.free_space') }}",
        "files": "{{ __('lang.files') }}",
        "visitors": "{{ __('lang.visitors') }}",
        "days": "{{ __('lang.days') }}",
        "monthly": "{{ __('lang.monthly') }}",
        "yearly": "{{ __('lang.yearly') }}",
        "search": "{{ __('lang.search') }}",
        "data_not_found": "{{ __('lang.data_not_found') }}",
        "success": "{{ __('lang.success') }}",
        "error": "{{ __('lang.error') }}",
        "not_found": "{{ __('lang.not_found') }}",
        "cancelled": "{{ __('lang.cancelled') }}",
        "no_data": "{{ __('lang.no_data') }}",
        "copied": "{{ __('lang.copied') }}",
        "copied_error": "{{ __('lang.copied_error') }}",
        "file_cannot_upload": "{{ __('lang.file_cannot_upload') }}",
        "file_max_size": "{{ __('lang.file_max_size', ['var' => formatKiloBytes(config('upload.MAX_FILE_SIZE'))]) }}",
        "file_max": "{{ __('lang.file_max', ['max' => config('upload.MAX_FILE_COUNT')]) }}",
        "no_file_selected": "{{ __('lang.no_file_selected') }}",
        "never": "{{ __('lang.never') }}",
        "format": "{{ __('lang.format') }}",
    };
    var sysVars = {
        "lang": "{{ LaravelLocalization::getCurrentLocale() }}",
        "lang_mode": "{{ LaravelLocalization::getCurrentLocaleDirection() }}",
        "color_mode": "{{ session('color') }}",
        "color_mode_selected": "{{ session('color_selected') ? 'true' : 'false' }}",
        "logo": "{{ img('other', setting('logo_light')) }}",
        "logo_dark": "{{ img('other', setting('logo_dark')) }}",
        "base_url": "{{ url('/') }}",
        "upload_url": "{{ LaravelLocalization::localizeUrl('/user/upload') }}",
        "upload_link_url": "{{ LaravelLocalization::localizeUrl('/user/upload-link') }}",
        "accepted_files": {!! uploadableTypesJs() !!},
        "max_file_count": {{ config('upload.MAX_FILE_COUNT') }},
        "max_file_size": {{ config('upload.MAX_FILE_SIZE') / 1024 }},
        @if (setting('recaptcha_status') == 1)
            "recaptcha_site_key": "{{ setting('recaptcha_site') }}"
        @endif
    };
</script>