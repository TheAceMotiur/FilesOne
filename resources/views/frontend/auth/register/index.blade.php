@extends("frontend.layouts.auth")
@section("content")
    @if (setting('recaptcha_status') == 1)
        {!! RecaptchaV3::initJs() !!}
    @endif
    <form 
        action="{{ LaravelLocalization::localizeUrl('/register') }}" 
        method="POST" 
        class="width-sm py-5 m-auto animate__animated{{ $errors->any() || session('error') ? ' animate__shakeX' : ' animate__zoomIn' }}">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h1 class="auth-title">{{ __('lang.register_account') }}</h1>
                    <p class="auth-subtitle m-0">{{ __('lang.register_account_subtitle') }}</p>
                </div>
                @if ($errors->any())
                    <div class="alert alert-1 show mb-4" role="alert">
                        @foreach ($errors->all() as $error)
                            <p class="m-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-2 show mb-4" role="alert">
                        <p class="m-0">{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-1 show mb-4" role="alert">
                        <p class="m-0">{{ session('error') }}</p>
                    </div>
                @endif
                <div class="mb-4">
                    <label for="name" class="form-label">{{ __('lang.name') }}</label>
                    <div class="input-icon">
                        <input 
                            type="text" 
                            class="form-control" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="form-label">{{ __('lang.email_address') }}</label>
                    <div class="input-icon">
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">{{ __('lang.password') }}</label>
                    <div class="input-icon">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            value="">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password-confirmation" class="form-label">{{ __('lang.password_again') }}</label>
                    <div class="input-icon">
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password-confirmation" 
                            name="password_confirmation" 
                            value="">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>
                <div class="form-check mb-4">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="terms-of-use" 
                        id="terms-of-use" 
                        value="1" 
                        {{ old('terms-of-use') == '1' ? ' checked' : '' }}>
                    <label class="form-check-label ms-2" for="terms-of-use">
                        @php
                            $termsUrl = LaravelLocalization::localizeUrl(
                                pageSlug('terms_of_use', true)
                            );
                        @endphp
                        {!! __('lang.accept_term', ['url' => $termsUrl]) !!}
                    </label>
                </div>
                @if (setting('recaptcha_status') == 1)
                    {!! RecaptchaV3::field('register') !!}
                @endif
                <div>
                    <button type="submit" class="btn btn-color-1 w-100">
                        <span class="text-uppercase">{{ __('lang.sign_up') }}</span>
                        <i class="fa-solid fa-circle-notch fa-spin d-none"></i>
                    </button>
                </div>
                @if (setting('recaptcha_status') == 1)
                    <p class="recaptcha mt-4">
                        {!! __('lang.recaptcha') !!}
                    </p>
                @endif
                <p class="auth-bottom text-md text-center mt-4 mb-0">
                    {{ __('lang.already_account') }} <a href="{{ LaravelLocalization::localizeUrl(pageSlug('login', true)) }}">{{ __('lang.sign_in') }}</a>
                </p>
                @csrf
            </div>
        </div>
        <div class="text-center mt-4">
            <a 
                href="{{ LaravelLocalization::localizeUrl('/') }}" 
                class="go-home">
                {{ __('lang.go_home') }}
            </a>
        </div>
    </form>
    <div class="cursor-followers d-none d-md-block">
        <div class="cursor-follower cursor-follower-inner"></div>
        <div class="cursor-follower cursor-follower-outer"></div>
    </div>
    <label class="switcher position-fixed" for="color-mode">
        <input 
            type="checkbox" 
            id="color-mode" 
            class="color-mode" 
            aria-label="Color mode" 
            @if (setting('default_color_mode') == 1)
                value="1"{{ session('color') == 'dark' ? ' checked' : '' }}>
            @else
                value="1"{{ session('color') == 'light' ? '' : ' checked' }}>
            @endif
        <span class="slider position-absolute">
            <i class="fa-solid fa-moon fa-lg pe-none position-absolute"></i>
            <i class="fa-regular fa-sun fa-lg pe-none position-absolute"></i>
        </span>
    </label>
    @if (Cookies::shouldDisplayNotice())
        <div class="cookie-opener d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round" class="pe-none">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 13v.01" />
                <path d="M12 17v.01" />
                <path d="M12 12v.01" />
                <path d="M16 14v.01" />
                <path d="M11 8v.01" />
                <path
                    d="M13.148 3.476l2.667 1.104a4 4 0 0 0 4.656 6.14l.053 .132a3 3 0 0 1 0 2.296c-.497 .786 -.838 1.404 -1.024 1.852c-.189 .456 -.409 1.194 -.66 2.216a3 3 0 0 1 -1.624 1.623c-1.048 .263 -1.787 .483 -2.216 .661c-.475 .197 -1.092 .538 -1.852 1.024a3 3 0 0 1 -2.296 0c-.802 -.503 -1.419 -.844 -1.852 -1.024c-.471 -.195 -1.21 -.415 -2.216 -.66a3 3 0 0 1 -1.623 -1.624c-.265 -1.052 -.485 -1.79 -.661 -2.216c-.198 -.479 -.54 -1.096 -1.024 -1.852a3 3 0 0 1 0 -2.296c.48 -.744 .82 -1.361 1.024 -1.852c.171 -.413 .391 -1.152 .66 -2.216a3 3 0 0 1 1.624 -1.623c1.032 -.256 1.77 -.476 2.216 -.661c.458 -.19 1.075 -.531 1.852 -1.024a3 3 0 0 1 2.296 0z" />
            </svg>
            <i class="fa-solid fa-x fa-fw fa-xl pe-none d-none"></i>
        </div>
    @endif
@stop