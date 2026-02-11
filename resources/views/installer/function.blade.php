<script>
    var cssVars = {
        "color5": getComputedStyle(document.documentElement).getPropertyValue('--color-5'),
        "color6": getComputedStyle(document.documentElement).getPropertyValue('--color-6'),
    };
    var langVars = {
        "locale": "{{ LaravelLocalization::getCurrentLocale() }}",
        "error": "{{ __('lang.error') }}",
        "cancelled": "{{ __('lang.cancelled') }}",
    };
    var installVars = {
        "database": "{{ url('install/database') }}",
        "settings": "{{ url('install/settings') }}",
        "finish": "{{ url('install/finish') }}",
    };
</script>
