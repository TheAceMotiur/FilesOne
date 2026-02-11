<aside id="cookies-policy" class="overflow-hidden">
    <div>
        <h2 class="card-heading pb-3 mb-3">{{ __('lang.cookie_title') }}</h2>
        <p class="text-md mb-3">{{ __('lang.cookie_text') }}</p>
        <div class="cookies-buttons">
            @cookieconsentbutton(
                action: 'accept.essentials',
                label: __('lang.only_essentials'),
                attributes: ['id' => 'cookies-essentials']
            )
            @cookieconsentbutton(
                action: 'accept.all',
                label: __('lang.accept_all'),
                attributes: ['id' => 'cookies-all', 'class' => 'mt-2']
            )
        </div>
    </div>
    <div class="cookies-details">
        <a class="d-flex align-items-center" data-bs-toggle="collapse" href="#customize-cookies" role="button" aria-expanded="false" aria-controls="customize-cookies">
            <span>{{ __('lang.customize_cookies') }}</span>
            <i class="fa-solid fa-sliders fa-fw ms-auto"></i>
        </a>
        <div class="collapse" id="customize-cookies">
            <form action="{{ route('cookieconsent.accept.configuration') }}" method="post" id="customize-cookies-form">
                <div class="customize-cookies-inner scroll-light pe-3 mt-3">
                    @foreach($cookies->getCategories() as $category)
                        @if ($category->key() === 'essentials')
                            <div class="form-switch d-flex gap-2">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->key() }}" role="switch" id="cookie-{{ $category->key() }}" checked disabled>
                                <label class="form-check-label my-auto" for="cookie-{{ $category->key() }}">{{ __('lang.essential_cookies') }}</label>
                            </div>
                            <p class="my-2">{{ __('lang.essential_cookies_text') }}</p>
                        @elseif ($category->key() === 'analytics')
                            <div class="form-switch d-flex gap-2">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->key() }}" role="switch" id="cookie-{{ $category->key() }}">
                                <label class="form-check-label my-auto" for="cookie-{{ $category->key() }}">{{ __('lang.analytics_cookies') }}</label>
                            </div>
                            <p class="my-2">{{ __('lang.analytics_cookies_text') }}</p>
                        @elseif ($category->key() === 'optional')
                            <div class="form-switch d-flex gap-2">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->key() }}" role="switch" id="cookie-{{ $category->key() }}">
                                <label class="form-check-label my-auto" for="cookie-{{ $category->key() }}">{{ __('lang.optional_cookies') }}</label>
                            </div>
                            <p class="my-2">{{ __('lang.optional_cookies_text') }}</p>
                        @endif
                    @endforeach
                </div>
                @csrf
                <button type="submit" class="btn btn-color-1 w-100 mt-3">{{ __('lang.save') }}</button>
            </form>
        </div>
    </div>
</aside>