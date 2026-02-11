<section class="page-header">
    <div 
        class="covered position-relative d-flex" 
        style="background: url({{ img('page', widget('blog_inner','header','bg_image'), 'lg') }});">
        <div class="mask"></div>
        <div class="small-container container text-center mx-auto">
            <p class="upper-title mb-1">
                {{ $post->categoryName }}
            </p>
            <h1 class="title position-relative mb-3">
                {{ $post->title }}
            </h1>
            <img 
                src="{{ url("/assets/image/title-arrow.svg") }}" 
                alt="Page header arrow" 
                width="56" 
                height="48" 
                data-after-anm="y" 
                class="animate animate__fadeIn">
        </div>
    </div>
</section>