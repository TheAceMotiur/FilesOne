<div class="cursor-followers d-none d-md-block">
    <div class="cursor-follower cursor-follower-inner"></div>
    <div class="cursor-follower cursor-follower-outer"></div>
</div>
<label class="switcher position-fixed d-none d-lg-inline-block" for="color-mode">
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
<div 
    class="go-to-top d-none d-md-grid" 
    data-cookies="{{ Cookies::shouldDisplayNotice() ? 'notaccepted' : 'accepted' }}">
    <span class="go-to-top-progress">
        <i class="fa-solid fa-chevron-up fa-fw fa-lg"></i>
    </span>
</div>
<div class="subscription-card-area">
    <div class="small-container container">
        <form 
            method="POST" 
            action="{{ LaravelLocalization::localizeUrl("/subscribe") }}" 
            id="subscription-form">
            <div class="subscription-card">
                <div class="subscription-card-heading text-center">
                    <p class="subscription-upper animate animate__fadeIn">
                        {{ __('lang.subscription_upper_title') }}
                    </p>
                    <h2 class="position-relative mb-3 animate animate__fadeIn" data-anm-delay="400ms">
                        {{ __('lang.subscription_title') }}
                    </h2>
                    <p class="text-md text-center animate animate__fadeIn" data-anm-delay="800ms">
                        {{ __('lang.subscription_text') }}
                    </p>
                </div>
                <div class="subscription-form d-flex">
                    <input type="text" class="form-control" name="subscription-email" placeholder="{{ __('lang.your_email') }}">
                    <button type="submit" class="btn btn-color-1" aria-label="Subscribe">
                        <i class="fa-solid fa-check fa-lg"></i>
                        <i class="fa-solid fa-slash fa-spin fa-sm d-none"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="footer-inner container">
    <div class="row gx-5">
        <div class="col-md-6 col-lg-5 mb-4 mb-lg-0">
            @if (session('color'))
                @if (session('color') == 'dark')
                    <img 
                        class="{{ setting('lazyload') == 1 ? 'lazy' : '' }}" 
                        id="footer-logo" 
                        alt="Website logo" width="135" height="30"
                        @if (setting('lazyload') == 1)
                            src="{{ url("assets/image/img-loader.svg") }}" 
                            data-src="{{ img('other', setting('logo_light')) }}">
                        @else
                            src="{{ img('other', setting('logo_light')) }}">
                        @endif
                @else
                    <img 
                        class="{{ setting('lazyload') == 1 ? 'lazy' : '' }}" 
                        id="footer-logo" 
                        alt="Website logo" width="135" height="30" 
                        @if (setting('lazyload') == 1)
                            src="{{ url("assets/image/img-loader.svg") }}" 
                            data-src="{{ img('other', setting('logo_dark')) }}">
                        @else
                            src="{{ img('other', setting('logo_dark')) }}">
                        @endif
                @endif
            @else
                @if (setting('default_color_mode') == 2)
                    <img 
                        class="{{ setting('lazyload') == 1 ? 'lazy' : '' }}" 
                        id="footer-logo" 
                        alt="Website logo" width="135" height="30"
                        @if (setting('lazyload') == 1)
                            src="{{ url("assets/image/img-loader.svg") }}" 
                            data-src="{{ img('other', setting('logo_light')) }}">
                        @else
                            src="{{ img('other', setting('logo_light')) }}">
                        @endif
                @else
                    <img 
                        class="{{ setting('lazyload') == 1 ? 'lazy' : '' }}" 
                        id="footer-logo" 
                        alt="Website logo" width="135" height="30" 
                        @if (setting('lazyload') == 1)
                            src="{{ url("assets/image/img-loader.svg") }}" 
                            data-src="{{ img('other', setting('logo_dark')) }}">
                        @else
                            src="{{ img('other', setting('logo_dark')) }}">
                        @endif
                @endif
            @endif
            @if (footerSettings('about'))
                <p class="footer-text mt-3 mb-0">{{ footerSettings('about') }}</p>
            @endif
            @if (setting('li_account') || setting('fb_account') || setting('x_account') || setting('in_account'))
                <div class="social d-flex gap-2 mt-3">
                    @if (setting('fb_account'))
                        <a href="{{ setting('fb_account') }}" target="_blank" rel="noreferrer"
                            aria-label="Facebook account">
                            <i class="fa-brands fa-facebook-f fa-fw fa-lg pe-none m-auto"></i>
                        </a>
                    @endif
                    @if (setting('x_account'))
                        <a href="{{ setting('x_account') }}" target="_blank" rel="noreferrer" aria-label="Twitter account">
                            <i class="fa-brands fa-x-twitter fa-fw fa-lg pe-none m-auto"></i>
                        </a>
                    @endif
                    @if (setting('in_account'))
                        <a href="{{ setting('in_account') }}" target="_blank" rel="noreferrer"
                            aria-label="Instagram account">
                            <i class="fa-brands fa-instagram fa-fw fa-lg pe-none m-auto"></i>
                        </a>
                    @endif
                    @if (setting('li_account'))
                        <a href="{{ setting('li_account') }}" target="_blank" rel="noreferrer"
                            aria-label="Linkedin account">
                            <i class="fa-brands fa-linkedin-in fa-fw fa-lg pe-none m-auto"></i>
                        </a>
                    @endif
                </div>
            @endif
        </div>
        <div class="col-sm-6 col-lg-2 mb-4 mb-lg-0">
            <h2 class="footer-title position-relative pb-3 mb-3">{{ __('lang.links') }}</h2>
            <ul class="nav flex-column">
                @if (footerSettings('link_1_name'))
                    <li class="nav-item mb-2">
                        <a 
                            class="footer-link" 
                            href="{{ LaravelLocalization::localizeUrl(footerSettings('link_1_url')) }}">
                            {{ footerSettings('link_1_name') }}
                        </a>
                    </li>
                @endif
                @if (footerSettings('link_2_name'))
                    <li class="nav-item mb-2">
                        <a 
                            class="footer-link" 
                            href="{{ LaravelLocalization::localizeUrl(footerSettings('link_2_url')) }}">
                            {{ footerSettings('link_2_name') }}
                        </a>
                    </li>
                @endif
                @if (footerSettings('link_3_name'))
                    <li class="nav-item mb-2">
                        <a 
                            class="footer-link" 
                            href="{{ LaravelLocalization::localizeUrl(footerSettings('link_3_url')) }}">
                            {{ footerSettings('link_3_name') }}
                        </a>
                    </li>
                @endif
                @if (footerSettings('link_4_name'))
                    <li class="nav-item mb-2">
                        <a 
                            class="footer-link" 
                            href="{{ LaravelLocalization::localizeUrl(footerSettings('link_4_url')) }}">
                            {{ footerSettings('link_4_name') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="col-sm-6 col-lg-2 mb-4 mb-lg-0">
            <h2 class="footer-title position-relative pb-3 mb-3">{{ __('lang.legal') }}</h2>
            <ul class="nav flex-column">
                @if (footerSettings('link_5_name'))
                    <li class="nav-item mb-2">
                        <a 
                            class="footer-link" 
                            href="{{ LaravelLocalization::localizeUrl(footerSettings('link_5_url')) }}">
                            {{ footerSettings('link_5_name') }}
                        </a>
                    </li>
                @endif
                @if (footerSettings('link_6_name'))
                    <li class="nav-item mb-2">
                        <a 
                            class="footer-link" 
                            href="{{ LaravelLocalization::localizeUrl(footerSettings('link_6_url')) }}">
                            {{ footerSettings('link_6_name') }}
                        </a>
                    </li>
                @endif
                @if (footerSettings('link_7_name'))
                    <li class="nav-item mb-2">
                        <a 
                            class="footer-link" 
                            href="{{ LaravelLocalization::localizeUrl(footerSettings('link_7_url')) }}">
                            {{ footerSettings('link_7_name') }}
                        </a>
                    </li>
                @endif
                @if (footerSettings('link_8_name'))
                    <li class="nav-item mb-2">
                        <a 
                            class="footer-link" 
                            href="{{ LaravelLocalization::localizeUrl(footerSettings('link_8_url')) }}">
                            {{ footerSettings('link_8_name') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div class="col-md-6 col-lg-3">
            <h2 class="footer-title position-relative pb-3 mb-3">{{ __('lang.support') }}</h2>
            <ul class="nav flex-column">
                @if (footerSettings('email'))
                    <li class="footer-text nav-item mb-2">
                        <i class="footer-icon fa-solid fa-envelope fa-fw me-2"></i>
                        {{ footerSettings('email') }}
                    </li>
                @endif
                @if (footerSettings('location'))
                    <li class="footer-text nav-item mb-2">
                        <i class="footer-icon fa-solid fa-location-dot fa-fw me-2"></i>
                        {{ footerSettings('location') }}
                    </li>
                @endif
            </ul>
        </div>
    </div>
    @if (footerSettings('copyright'))
        <div class="footer-bottom text-center py-4">
            <p class="footer-text m-0">
                {{ footerSettings('copyright') }}
            </p>
        </div>
    @endif
</div>