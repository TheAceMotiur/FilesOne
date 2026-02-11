<script>
    document.addEventListener("DOMContentLoaded", () => {

        @if (downloadSetting('adblock_blocker'))
            function {{ $randomFunction }}(callback) {
                var ADS_URL = 'https://pagead2.googlesyndication.com' 
                    + '/pagead/js/adsbygoogle.js';

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == XMLHttpRequest.DONE) {
                        callback(xhr.status === 0 || xhr.responseURL !== ADS_URL);
                    }
                };
                xhr.open('HEAD', ADS_URL, true);
                xhr.send(null);
            }
            {{ $randomFunction }}(function(adsBlocked) {
                if (adsBlocked ) {
                    var container = document.querySelector('.{{ $randomClass }}');
                    var inner = '<div class="adblocker-notice my-5">'
                        + '<div class="d-flex flex-column">'
                        + '<div class="mx-auto mb-3">'
                        + '<i class="fa-solid fa-triangle-exclamation fa-fw fa-4x"></i>'
                        + '</div>'
                        + '<p class="text-center m-0">{{ __("lang.adblock_notice") }}</p>'
                        + '</div></div>';
                    container.innerHTML = inner;
                }
            });
        @endif

        @if (downloadSetting('js_codes'))
            {!! downloadSetting('js_codes') !!}
        @endif
    });
</script>