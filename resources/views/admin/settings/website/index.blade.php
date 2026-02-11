@extends("admin.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
        <div class="card">
            <div class="card-body">
                <div class="setting-tabs nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a href="?tab=website" class="nav-link active" id="v-pills-website-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-website" role="tab" aria-controls="v-pills-website" aria-selected="true">{{ __('lang.website') }}</a>
                    <a href="?tab=logo" class="nav-link" id="v-pills-logo-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-logo" role="tab" aria-controls="v-pills-logo" aria-selected="false">{{ __('lang.logo_favicon') }}</a>
                    <a href="?tab=preferences" class="nav-link" id="v-pills-preferences-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-preferences" role="tab" aria-controls="v-pills-preferences" aria-selected="false">{{ __('lang.preferences') }}</a>
                    <a href="?tab=social-media" class="nav-link" id="v-pills-social-media-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-social-media" role="tab" aria-controls="v-pills-social-media" aria-selected="false">{{ __('lang.social_media') }}</a>
                    <a href="?tab=auth" class="nav-link" id="v-pills-auth-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-auth" role="tab" aria-controls="v-pills-auth" aria-selected="false">{{ __('lang.social_auth') }}</a>
                    <a href="?tab=recaptcha" class="nav-link" id="v-pills-recaptcha-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-recaptcha" role="tab" aria-controls="v-pills-recaptcha" aria-selected="false">Google reCAPTCHA</a>
                    <a href="?tab=ip2location" class="nav-link" id="v-pills-ip2location-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-ip2location" role="tab" aria-controls="v-pills-ip2location" aria-selected="false">ip2location</a>
                    <a href="?tab=additional" class="nav-link" id="v-pills-additional-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-additional" role="tab" aria-controls="v-pills-additional" aria-selected="false">{{ __('lang.additional') }}</a>
                    <a href="?tab=cache" class="nav-link" id="v-pills-cache-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-cache" role="tab" aria-controls="v-pills-cache" aria-selected="false">{{ __('lang.clear_cache') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-lg-9">
        @if ($errors->any())
            <div class="alert alert-1 alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <p class="m-0">{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-2 alert-dismissible fade show" role="alert">
                <p class="m-0">{{ session('success') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-1 alert-dismissible fade show" role="alert">
                <p class="m-0">{{ session('error') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-website" role="tabpanel" aria-labelledby="v-pills-website-tab" tabindex="0">
                <form 
                    method="POST" 
                    action="{{ LaravelLocalization::localizeUrl('/admin/settings/website') }}" 
                    enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.website_settings') }}</div>
                            <div class="row">
                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ __('lang.website_name') }}</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ setting('name') }}" autocomplete="off">
                                </div>
                                <div class="mb-4">
                                    <label for="description" class="form-label">{{ __('lang.website_description') }}</label>
                                    <textarea class="form-control" name="description" id="description" rows="3" autocomplete="off">{{ setting('description') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="keywords" class="form-label">{{ __('lang.website_keywords') }}</label>
                                    <input type="text" class="form-control" name="keywords" id="keywords" value="{{ setting('keywords') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="og-title" class="form-label">{{ __('lang.og_title') }}</label>
                                    <input type="text" class="form-control" name="og-title" id="og-title" value="{{ setting('og_title') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="og-image" class="form-label">{{ __('lang.og_image') }}</label>
                                    <input type="file" class="form-control" name="og-image" id="og-image">
                                    @if (setting('og_image'))
                                        <div class="img-container d-flex p-3 mt-4">
                                            <div class="covered" style="background: url({{ img('other', setting('og_image')) }});"></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-4">
                                    <label for="og-description" class="form-label">{{ __('lang.og_description') }}</label>
                                    <textarea class="form-control" name="og-description" id="og-description" rows="3" autocomplete="off">{{ setting('og_description') }}</textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-logo" role="tabpanel" aria-labelledby="v-pills-logo-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/logo') }}" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.logo_favicon') }}</div>
                            <div class="row">
                                <div class="col-lg-6 col-xxl-4 mb-4">
                                    <label for="logo-light" class="form-label">{{ __('lang.website_logo_light') }}</label>
                                    <input type="file" class="form-control" name="logo-light" id="logo-light">
                                    @if (setting('logo_light'))
                                        <div class="img-container d-flex p-3 mt-4">
                                            <div class="covered" style="background: url({{ img('other', setting('logo_light')) }});"></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-xxl-4 mb-4">
                                    <label for="logo-dark" class="form-label">{{ __('lang.website_logo_dark') }}</label>
                                    <input type="file" class="form-control" name="logo-dark" id="logo-dark">
                                    @if (setting('logo_dark'))
                                        <div class="img-container d-flex p-3 mt-4">
                                            <div class="covered" style="background: url({{ img('other', setting('logo_dark')) }});"></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-6 col-xxl-4 mb-4">
                                    <label for="favicon" class="form-label">{{ __('lang.website_favicon') }}</label>
                                    <input type="file" class="form-control" name="favicon" id="favicon">
                                    @if (setting('favicon'))
                                        <div class="img-container d-flex p-3 mt-4">
                                            <div class="covered" style="background: url({{ img('other', setting('favicon')) }});"></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-preferences" role="tabpanel" aria-labelledby="v-pills-preferences-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/preferences') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.preferences') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="time-zone" class="form-label">{{ __('lang.time_zone') }}</label>
                                    <select class="form-select" name="time-zone" id="time-zone" aria-label="Time Zone">
                                        @foreach (timezone_identifiers_list() as $timezone)
                                            <option value="{{ $timezone }}"{{ $timezone == setting('time_zone') ? ' selected' : '' }}>
                                                {{ $timezone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="time-format" class="form-label">{{ __('lang.time_format') }}</label>
                                    <select class="form-select" name="time-format" id="time-format" aria-label="Time Format">
                                        <option value="1"{{ setting('time_format') == 1 ? ' selected' : '' }}>12</option>
                                        <option value="2"{{ setting('time_format') == 2 ? ' selected' : '' }}>24</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="loader" class="form-label">{{ __('lang.loader') }}</label>
                                    <select class="form-select" name="loader" id="loader" aria-label="Loader">
                                        <option value="1"{{ setting('loader') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ setting('loader') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="loader-style" class="form-label">{{ __('lang.loader_style') }}</label>
                                    <select class="form-select" name="loader-style" id="loader-style" aria-label="Loader Style">
                                        <option value="audio"{{ setting('loader_style') == "audio" ? ' selected' : '' }}>{{ __('lang.audio') }}</option>
                                        <option value="ball-triangle"{{ setting('loader_style') == "ball-triangle" ? ' selected' : '' }}>{{ __('lang.ball_triangle') }}</option>
                                        <option value="bars"{{ setting('loader_style') == "bars" ? ' selected' : '' }}>{{ __('lang.bars') }}</option>
                                        <option value="circles"{{ setting('loader_style') == "circles" ? ' selected' : '' }}>{{ __('lang.circles') }}</option>
                                        <option value="grid"{{ setting('loader_style') == "grid" ? ' selected' : '' }}>{{ __('lang.grid') }}</option>
                                        <option value="hearts"{{ setting('loader_style') == "hearts" ? ' selected' : '' }}>{{ __('lang.hearts') }}</option>
                                        <option value="oval"{{ setting('loader_style') == "oval" ? ' selected' : '' }}>{{ __('lang.oval') }}</option>
                                        <option value="puff"{{ setting('loader_style') == "puff" ? ' selected' : '' }}>{{ __('lang.puff') }}</option>
                                        <option value="rings"{{ setting('loader_style') == "rings" ? ' selected' : '' }}>{{ __('lang.rings') }}</option>
                                        <option value="spinning-circles"{{ setting('loader_style') == "spinning-circles" ? ' selected' : '' }}>{{ __('lang.spinning_circles') }}</option>
                                        <option value="tail-spin"{{ setting('loader_style') == "tail-spin" ? ' selected' : '' }}>{{ __('lang.tail_spin') }}</option>
                                        <option value="three-dots"{{ setting('loader_style') == "three-dots" ? ' selected' : '' }}>{{ __('lang.three_dots') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="lazyload" class="form-label">{{ __('lang.lazy_loading') }}</label>
                                    <select class="form-select" name="lazyload" id="lazyload" aria-label="Lazyload">
                                        <option value="1"{{ setting('lazyload') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ setting('lazyload') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="minify" class="form-label">{{ __('lang.minify') }}</label>
                                    <select class="form-select" name="minify" id="minify" aria-label="Minify">
                                        <option value="1"{{ setting('minify') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ setting('minify') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="defer" class="form-label">{{ __('lang.defer') }}</label>
                                    <select class="form-select" name="defer" id="defer" aria-label="Defer">
                                        <option value="1"{{ setting('defer') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ setting('defer') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="email-verification" class="form-label">{{ __('lang.email_verification') }}</label>
                                    <select class="form-select" name="email-verification" id="email-verification" aria-label="Email Verification">
                                        <option value="1"{{ setting('email_verification') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ setting('email_verification') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="default-color-mode" class="form-label">{{ __('lang.default_color_mode') }}</label>
                                    <select class="form-select" name="default-color-mode" id="default-color-mode" aria-label="Default Color Mode">
                                        <option value="1"{{ setting('default_color_mode') == 1 ? ' selected' : '' }}>{{ __('lang.light') }}</option>
                                        <option value="2"{{ setting('default_color_mode') == 2 ? ' selected' : '' }}>{{ __('lang.dark') }}</option>
                                    </select>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-social-media" role="tabpanel" aria-labelledby="v-pills-social-media-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/contact') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.social_media') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="fb-account" class="form-label">{{ __('lang.fb_account') }}</label>
                                    <input type="text" class="form-control" name="fb-account" id="fb-account" value="{{ setting('fb_account') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="x-account" class="form-label">{{ __('lang.x_account') }}</label>
                                    <input type="text" class="form-control" name="x-account" id="x-account" value="{{ setting('x_account') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="in-account" class="form-label">{{ __('lang.in_account') }}</label>
                                    <input type="text" class="form-control" name="in-account" id="in-account" value="{{ setting('in_account') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="li-account" class="form-label">{{ __('lang.li_account') }}</label>
                                    <input type="text" class="form-control" name="li-account" id="li-account" value="{{ setting('li_account') }}" autocomplete="off">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-auth" role="tabpanel" aria-labelledby="v-pills-auth-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/auth') }}">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.social_auth') }}</div>
                            <div class="row">
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="go-status" class="form-label">{{ __('lang.go_status') }}</label>
                                    <select class="form-select" name="go-status" id="go-status" aria-label="Google Status">
                                        <option value="1"{{ setting('go_status') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ setting('go_status') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="w-100"></div>
                                <div class="col-lg-6 mb-4">
                                    <label for="go-client" class="form-label">{{ __('lang.go_client') }}</label>
                                    <input type="text" class="form-control" name="go-client" id="go-client" value="{{ setting('go_client') }}" autocomplete="off">
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label for="go-secret" class="form-label">{{ __('lang.go_secret') }}</label>
                                    <input type="text" class="form-control" name="go-secret" id="go-secret" value="{{ setting('go_secret') }}" autocomplete="off">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-recaptcha" role="tabpanel" aria-labelledby="v-pills-recaptcha-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/recaptcha') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">Google reCAPTCHA</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="recaptcha-status" class="form-label">{{ __('lang.status') }}</label>
                                    <select class="form-select" name="recaptcha-status" id="recaptcha-status" aria-label="reCAPTCHA Status">
                                        <option value="1"{{ setting('recaptcha_status') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ setting('recaptcha_status') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <label for="recaptcha-site" class="form-label">{{ __('lang.site_key') }}</label>
                                    <input type="text" class="form-control" name="recaptcha-site" id="recaptcha-site" 
                                        value="{{ setting('recaptcha_site') }}" autocomplete="off">
                                </div>
                                <div class="mb-4">
                                    <label for="recaptcha-secret" class="form-label">{{ __('lang.secret_key') }}</label>
                                    <input type="text" class="form-control" name="recaptcha-secret" id="recaptcha-secret" 
                                        value="{{ setting('recaptcha_secret') }}" autocomplete="off">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-ip2location" role="tabpanel" aria-labelledby="v-pills-ip2location-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/ip2location') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">ip2location</div>
                            <div class="row">
                                <div class="mb-4">
                                    <label for="ip2location-token" class="form-label">{{ __('lang.ip2location_token') }}</label>
                                    <input type="text" class="form-control" name="ip2location-token" id="ip2location-token" 
                                        value="{{ setting('ip2location_token') }}" autocomplete="off">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                                <p class="text-center mt-3 mb-0">
                                    Your website uses IP2Location.io <a href="https://www.ip2location.io">IP geolocation</a> web service.
                                </p>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-additional" role="tabpanel" aria-labelledby="v-pills-additional-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/additional') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.additional_settings') }}</div>
                            <div class="row">
                                <div class="mb-4">
                                    <label for="additional-css" class="form-label">{{ __('lang.additional') }} CSS</label>
                                    <textarea class="form-control" name="additional-css" id="additional-css" rows="3" 
                                        autocomplete="off">{{ setting('additional_css') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="additional-js" class="form-label">{{ __('lang.additional') }} JS</label>
                                    <textarea class="form-control" name="additional-js" id="additional-js" rows="3" 
                                        autocomplete="off">{{ setting('additional_js') }}</textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-cache" role="tabpanel" aria-labelledby="v-pills-cache-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/cache') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.clear_cache') }}</div>
                            <p class="no-data">{{ __('lang.clear_cache_text') }}</p>
                            <div class="text-center">
                                <button type="submit" class="btn btn-color-1">
                                    <i class="spinner fa-solid fa-check me-1"></i>
                                    {{ __('lang.clear') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@stop