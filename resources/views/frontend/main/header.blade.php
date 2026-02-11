<meta charset="utf-8">
<meta content='width=device-width, initial-scale=1, user-scalable=1, minimum-scale=1, maximum-scale=5' name='viewport'/>
{!! isset($seoData) ? seoBlock($seoData) : '' !!}
<meta name="token" content="{{ csrf_token() }}">
{!! loadCSS($pageKey) !!}
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
@if (setting('additional_css'))
    <style>
        {!! setting('additional_css') !!}
    </style>
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
        "locale": "{{ langSetting('base_language') == LaravelLocalization::getCurrentLocale() ? '' : LaravelLocalization::getCurrentLocale() }}",
        "success": "{{ __('lang.success') }}",
        "error": "{{ __('lang.error') }}",
        "not_found": "{{ __('lang.not_found') }}",
        "cancelled": "{{ __('lang.cancelled') }}",
        "copied": "{{ __('lang.copied') }}",
        "copied_error": "{{ __('lang.copied_error') }}",
        "file_cannot_upload": "{{ __('lang.file_cannot_upload') }}",
        "file_max_size": "{{ __('lang.file_max_size', ['var' => formatKiloBytes(config('upload.MAX_FILE_SIZE'))]) }}",
        "file_max": "{{ __('lang.file_max', ['max' => config('upload.MAX_FILE_COUNT')]) }}",
        "no_file_selected": "{{ __('lang.no_file_selected') }}",
        "format": "{{ __('lang.format') }}",
        "pay_now": "{{ __('lang.pay_now') }}",
    };
    var sysVars = {
        "lang": "{{ LaravelLocalization::getCurrentLocale() }}",
        "lang_mode": "{{ LaravelLocalization::getCurrentLocaleDirection() }}",
        "color_mode": "{{ session('color') }}",
        "color_mode_selected": "{{ session('color_selected') ? 'true' : 'false' }}",
        "logo": "{{ img('other', setting('logo_light')) }}",
        "logo_dark": "{{ img('other', setting('logo_dark')) }}",
        "base_url": "{{ url('/') }}",
        "upload_url": "{{ LaravelLocalization::localizeUrl('/upload') }}",
        "upload_link_url": "{{ LaravelLocalization::localizeUrl('/upload-link') }}",
        "accepted_files": {!! uploadableTypesJs() !!},
        "max_file_count": {{ config('upload.MAX_FILE_COUNT') }},
        "max_file_size": {{ config('upload.MAX_FILE_SIZE') / 1024 }},
        "download_countdown": "{{ countdownTime() }}",
        @if (setting('recaptcha_status') == 1)
            "recaptcha_site_key": "{{ setting('recaptcha_site') }}"
        @endif
    };
</script>