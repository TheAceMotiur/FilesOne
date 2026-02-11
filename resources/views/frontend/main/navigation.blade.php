<nav class="navbar navbar-expand-lg fixed-top" aria-label="Main navigation">
    <div class="container-xl p-2">
        <a class="my-auto" href="{{ LaravelLocalization::localizeUrl("/")}}">
            @if (session('color'))
                @if (session('color') == 'dark')
                    <img 
                        class="{{ setting('lazyload') == 1 ? 'lazy' : '' }}" 
                        id="header-logo" 
                        alt="Website logo" width="135" height="30"
                        @if (setting('lazyload') == 1)
                            src="{{ url("assets/image/img-loader-white.svg") }}" 
                            data-src="{{ img('other', setting('logo_light')) }}">
                        @else
                            src="{{ img('other', setting('logo_light')) }}">
                        @endif
                @else
                    <img 
                        class="{{ setting('lazyload') == 1 ? 'lazy' : '' }}" 
                        id="header-logo" 
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
                        id="header-logo" 
                        alt="Website logo" width="135" height="30"
                        @if (setting('lazyload') == 1)
                            src="{{ url("assets/image/img-loader-white.svg") }}" 
                            data-src="{{ img('other', setting('logo_light')) }}">
                        @else
                            src="{{ img('other', setting('logo_light')) }}">
                        @endif
                @else
                    <img 
                        class="{{ setting('lazyload') == 1 ? 'lazy' : '' }}" 
                        id="header-logo" 
                        alt="Website logo" width="135" height="30" 
                        @if (setting('lazyload') == 1)
                            src="{{ url("assets/image/img-loader.svg") }}" 
                            data-src="{{ img('other', setting('logo_dark')) }}">
                        @else
                            src="{{ img('other', setting('logo_dark')) }}">
                        @endif
                @endif
            @endif
        </a>
        <ul class="navbar-nav d-none d-lg-flex gap-lg-5 mx-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link active" href="{{ LaravelLocalization::localizeUrl("/")}}">{{ __('lang.home') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ LaravelLocalization::localizeUrl(pageSlug('pricing', true)) }}">{{ __('lang.pricing') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ LaravelLocalization::localizeUrl(pageSlug('blog', true)) }}">{{ __('lang.blog') }}</a>
            </li>
            @if (affiliateSetting('status') == 1)
                <li class="nav-item">
                    <a class="nav-link" href="{{ LaravelLocalization::localizeUrl(pageSlug('affiliate', true)) }}">{{ __('lang.affiliate') }}</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" href="{{ LaravelLocalization::localizeUrl(pageSlug('contact', true)) }}">{{ __('lang.contact') }}</a>
            </li>
        </ul>
        <div class="d-flex gap-2 ms-auto ms-lg-0">
            @if (languages() != null && languages() && langSetting('language_switcher') == 1)
                <div class="dropdown language-select">
                    <button 
                        class="btn btn-header" 
                        type="button" 
                        aria-label="Languages"
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                        <span class="fi fi-{{ LaravelLocalization::getCurrentLocale() }} fis rounded-circle fa-lg fa-fw pe-none"></span>
                    </button>
                    <ul class="dropdown-menu text-center">
                        @foreach (languages() as $language)
                            @if ('fi fi-' . LaravelLocalization::getCurrentLocale() . ' fis' != $language['flag'])
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        hreflang="{{ $language['code'] }}"
                                        href="{{ LaravelLocalization::getLocalizedURL($language['code'], null, [], true) }}">
                                        <span class="{{ $language['flag'] }} rounded-circle fa-lg fa-fw pe-none"></span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Auth::check())
                <div class="dropdown">
                    <button class="btn btn-header" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
                        <i class="fa-solid fa-user fa-lg fa-fw pe-none"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-3">
                        @if (Auth::user()->type == 1)
                            <li><a class="dropdown-item" href="{{ LaravelLocalization::localizeUrl('/user/overview') }}">{{ __('lang.dashboard') }}</a></li> 
                        @else
                            <li><a class="dropdown-item" href="{{ LaravelLocalization::localizeUrl('/admin/overview') }}">{{ __('lang.dashboard') }}</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ LaravelLocalization::localizeUrl("logout") }}">{{ __('lang.logout') }}</a></li>
                        @if (Auth::user()->type == 1)
                            <li class="user-menu-alert p-3 mx-2 mt-2">
                                <p class="mb-0">{{ __('lang.my_plan') }}: {{ myPlan()['name'] }}</p>
                                @if (!myPlan()['free'])
                                    <p class="mt-3 mb-0">
                                        {{ 
                                            __(
                                                'lang.remaining', 
                                                [
                                                    'remaining0' => myPlan()['remaining'][0], 
                                                    'remaining1' => myPlan()['remaining'][1], 
                                                    'remaining2' => myPlan()['remaining'][2]
                                                ]
                                            ) 
                                        }}
                                    </p>
                                @endif
                            </li>
                        @endif
                    </ul>
                </div>
            @else
                <a class="btn btn-color-1 my-auto" 
                    href="{{ LaravelLocalization::localizeUrl(pageSlug('login', true)) }}">
                    {{ __('lang.login') }}
                </a>
            @endif
            <div class="d-block d-lg-none">
                <button 
                    class="btn btn-header" 
                    type="button" 
                    data-bs-toggle="offcanvas" 
                    data-bs-target="#side-menu" 
                    aria-controls="side-menu" 
                    aria-label="Open side menu">
                    <i class="fa-solid fa-bars fa-lg fa-fw pe-none"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
<div id="side-menu" class="offcanvas offcanvas-start" tabindex="-1"> 
    <div class="offcanvas-header">
        <button 
            type="button" 
            class="btn-close ms-auto" 
            data-bs-dismiss="offcanvas" 
            aria-label="Close">
        </button> 
    </div> 
    <div class="offcanvas-body scroll-light"> 
        <nav class="side-menu d-flex flex-column h-100"> 
            <ul class="nav flex-column" id="nav_accordion">
                <li class="nav-item"> 
                    <a class="nav-link p-0" href="{{ LaravelLocalization::localizeUrl("/") }}"> 
                        {{ __('lang.home') }} 
                    </a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link p-0" href="{{ LaravelLocalization::localizeUrl(pageSlug('pricing', true)) }}"> 
                        {{ __('lang.pricing') }} 
                    </a>
                </li>
                <li class="nav-item"> 
                    <a class="nav-link p-0" href="{{ LaravelLocalization::localizeUrl(pageSlug('blog', true)) }}"> 
                        {{ __('lang.blog') }} 
                    </a>
                </li>
                @if (affiliateSetting('status') == 1)
                    <li class="nav-item"> 
                        <a class="nav-link p-0" href="{{ LaravelLocalization::localizeUrl(pageSlug('affiliate', true)) }}"> 
                            {{ __('lang.affiliate') }} 
                        </a>
                    </li>
                @endif
                <li class="nav-item"> 
                    <a class="nav-link p-0" href="{{ LaravelLocalization::localizeUrl(pageSlug('contact', true)) }}"> 
                        {{ __('lang.contact') }} 
                    </a>
                </li>
            </ul>
        </nav>
        <div>
            <label class="switcher position-fixed" for="color-mode-mobile">
                <input 
                    type="checkbox" 
                    id="color-mode-mobile" 
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
        </div>
    </div> 
</div>