<section class="page-header">
    <div 
        class="covered position-relative d-flex" 
        @if ($content['header']['bg_image'])
            style="background: url({{ img('page', $content['header']['bg_image'], 'lg') }});">
        @else
            >
        @endif
        <div class="mask"></div>
        <div class="small-container container text-center mx-auto">
            @if ($content['header']['upper_title'])
                <p class="upper-title mb-1">
                    {{ $content['header']['upper_title'] }}
                </p>
            @endif
            @if ($content['header']['title'])
                <h1 class="title position-relative mb-3">
                    {{ $content['header']['title'] }}
                </h1>
            @endif
            @if ($content['header']['upper_title'] || $content['header']['title'])
                <img 
                    src="{{ url("/assets/image/title-arrow.svg") }}" 
                    alt="Page header arrow" 
                    width="56" 
                    height="48" 
                    data-after-anm="y" 
                    class="animate animate__fadeIn">
            @endif
        </div>
    </div>
</section>