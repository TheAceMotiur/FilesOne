<section class="blog-page">
    <div class="container">
        @if ($posts)
            <div class="row row-gap-5 gx-5" id="blog-posts">
                @foreach ($posts as $post)
                    <div class="col-md-6 col-xl-4">
                        <article class="blog-card">
                            <div class="blog-image overflow-hidden">
                                <div class="covered position-relative w-100 h-100{{ setting('lazyload') == 1 ? ' lazy' : '' }}" 
                                    @if (setting('lazyload') == 1)
                                        data-bg="{{ img('blog', $post['featured_photo'], 'lg') }}">
                                    @else
                                        style="background: url({{ img('blog', $post['featured_photo'], 'lg') }});">
                                    @endif
                                </div>
                            </div>
                            <div class="card mx-4">
                                <div class="card-body text-center">
                                    <p class="blog-category mb-1">
                                        {{ $post['category'] }}
                                    </p>
                                    <h2 class="blog-title m-0">
                                        <a href="{{ LaravelLocalization::localizeUrl(pageSlug('blog_inner') . "/" . $post['slug']) }}">
                                            {{ $post['title'] }}
                                        </a>
                                    </h2>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            @if ($loadMore)
                <div class="text-center mt-5">
                    <button 
                        type="button" 
                        class="load-more-posts btn btn-color-1" 
                        data-slug="{{ pageSlug('blog') }}" 
                        data-limit="6" 
                        data-offset="6">
                        {{ __('lang.load_more') }}
                    </button>
                </div>
            @endif
        @else
            <p class="text-md text-center mb-0">{{ __('lang.not_result') }}</p>
        @endif
    </div>
</section>