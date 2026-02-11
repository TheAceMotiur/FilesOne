<section class="home-features-area">
    <div class="medium-container container">
        <div class="feature-container d-flex flex-column flex-md-row align-items-center justify-content-center gap-5">
            <div>
                <div 
                    class="feature-image covered animate animate__fadeIn{{ setting('lazyload') == 1 ? ' lazy' : '' }}" 
                    data-anm-delay="400ms" 
                    @if (setting('lazyload') == 1)
                        data-bg="{{ img('page', widget('home','features','feature_1_image'), 'lg') }}">
                    @else
                        style="background: url({{ img('page', widget('home','features','feature_1_image'), 'lg') }})">
                    @endif
                </div>
            </div>
            <div class="feature-text-area d-flex flex-column text-center text-md-start">
                <h2 class="feature-title mb-3 animate animate__fadeIn" data-anm-delay="1600ms">
                    {{ widget('home','features','feature_1_title') }}
                </h2>
                <p class="feature-text text-md mb-0 animate animate__fadeIn" data-anm-delay="2000ms">
                    {{ widget('home','features','feature_1_text') }}
                </p>
            </div>
        </div>
        <div class="feature-container d-flex flex-md-row-reverse flex-column align-items-center justify-content-center gap-5">
            <div>
                <div 
                    class="feature-image covered animate animate__fadeIn{{ setting('lazyload') == 1 ? ' lazy' : '' }}" 
                    data-anm-delay="400ms" 
                    @if (setting('lazyload') == 1)
                        data-bg="{{ img('page', widget('home','features','feature_2_image'), 'lg') }}">
                    @else
                        style="background: url({{ img('page', widget('home','features','feature_2_image'), 'lg') }})">
                    @endif
                </div>
            </div>
            <div class="feature-text-area d-flex flex-column text-center text-md-start">
                <h2 class="feature-title mb-3 animate animate__fadeIn" data-anm-delay="1600ms">
                    {{ widget('home','features','feature_2_title') }}
                </h2>
                <p class="feature-text text-md mb-0 animate animate__fadeIn" data-anm-delay="2000ms">
                    {{ widget('home','features','feature_2_text') }}
                </p>
            </div>
        </div>
        <div class="feature-container d-flex flex-column flex-md-row align-items-center justify-content-center gap-5">
            <div>
                <div 
                    class="feature-image covered animate animate__fadeIn{{ setting('lazyload') == 1 ? ' lazy' : '' }}" 
                    data-anm-delay="400ms" 
                    @if (setting('lazyload') == 1)
                        data-bg="{{ img('page', widget('home','features','feature_3_image'), 'lg') }}">
                    @else
                        style="background: url({{ img('page', widget('home','features','feature_3_image'), 'lg') }})">
                    @endif
                </div>
            </div>
            <div class="feature-text-area d-flex flex-column text-center text-md-start">
                <h2 class="feature-title mb-3 animate animate__fadeIn" data-anm-delay="1600ms">
                    {{ widget('home','features','feature_3_title') }}
                </h2>
                <p class="feature-text text-md mb-0 animate animate__fadeIn" data-anm-delay="2000ms">
                    {{ widget('home','features','feature_3_text') }}
                </p>
            </div>
        </div>
    </div>
</section>