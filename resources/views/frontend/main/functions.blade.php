<script>
    document.addEventListener("DOMContentLoaded", () => {
        @if (setting('lazyload') == 1)
            /* Init lazyload library */
            lazy();
        @endif
        @if (isset($scroller) || session('scroller'))
            /* Scroll to a element general function */
            scroller("{!! isset($scroller) ? $scroller : session('scroller') !!}");
        @endif
        @if (setting('loader') == 1)
            /* Website loader */
            loader();
        @endif
    });
</script>
@if (setting('additional_js'))
    {!! setting('additional_js') !!}
@endif