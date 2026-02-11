<section class="page-header">
    <div 
        class="covered position-relative d-flex" 
        style="background: url({{ img('page', widget('privacy_policy','header','bg_image'), 'lg') }});">
        <div class="mask"></div>
        <div class="small-container container text-center mx-auto">
            @if (widget('privacy_policy','header','upper_title'))
                <p class="upper-title mb-1">
                    {{ widget('privacy_policy','header','upper_title') }}
                </p>
            @endif
            @if (widget('privacy_policy','header','title'))
                <h1 class="title position-relative mb-3">
                    {{ widget('privacy_policy','header','title') }}
                </h1>
            @endif
            @if (widget('privacy_policy','header','upper_title') || widget('privacy_policy','header','title'))
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