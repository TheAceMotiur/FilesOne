<section class="contact-form-area">
    <div class="container position-relative">
        <div 
            class="contact-image covered d-none d-lg-block position-absolute animate animate__fadeIn{{ setting('lazyload') == 1 ? ' lazy' : '' }}" 
            data-anm-delay="400ms" 
            @if (setting('lazyload') == 1)
                data-bg="{{ img('page', widget('contact','form','image'), 'lg') }}">
            @else
                style="background: url({{ img('page', widget('contact','form','image'), 'lg') }})">
            @endif
        </div>
        <div class="row justify-content-lg-end">
            <div class="col-lg-7">
                <div class="form-card card justify-content-center p-5">
                    @if (widget('contact','form','upper_title') || widget('contact','form','title') || widget('contact','form','text'))
                        <div class="section-heading text-center mb-4">
                            @if (widget('contact','form','upper_title'))
                                <p class="section-heading-upper animate animate__fadeIn" data-anm-delay="1200ms">
                                    {{ widget('contact','form','upper_title') }}
                                </p>
                            @endif
                            @if (widget('contact','form','title'))
                                <h2 class="position-relative pb-5 mb-4 animate animate__fadeIn" data-anm-delay="1600ms">
                                    {{ widget('contact','form','title') }}
                                </h2>
                            @endif
                            @if (widget('contact','form','text'))
                                <p class="section-heading-text mb-0 animate animate__fadeIn" data-anm-delay="2000ms">
                                    {{ widget('contact','form','text') }}
                                </p>
                            @endif
                        </div>
                    @endif
                    <div class="form-card-inner animate animate__fadeIn" data-anm-delay="2400ms">
                        <form action="#" id="contact-form" method="post">
                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <input type="text" name="name" class="form-control" placeholder="{{ __('lang.name') }}" value="{{ old('name') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <input type="email" name="email" class="form-control" placeholder="{{ __('lang.email') }}" value="{{ old('email') }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="mb-4">
                                <input type="text" name="subject" class="form-control" placeholder="{{ __('lang.subject') }}" value="{{ old('subject') }}" autocomplete="off">
                            </div>
                            <div class="mb-4">
                                <textarea name="message" class="form-control" rows="4" placeholder="{{ __('lang.message') }}" autocomplete="off">{{ old('message') }}</textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-color-1">{{ __('lang.send') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>