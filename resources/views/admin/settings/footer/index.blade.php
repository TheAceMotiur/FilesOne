@extends("admin.layouts.dashboard")
@section("content")
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl('/admin/settings/footer') }}" 
    enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-color-1 w-100">
                        {{ __('lang.save') }}
                    </button>
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
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-heading pb-3 mb-3">{{ __('lang.area') }} #1</div>
                    <div class="row">
                        <div>
                            <label for="about" class="form-label">{{ __('lang.about') }}</label>
                            <textarea class="form-control" name="about" id="about" autocomplete="off">{{ footerSettings('about', false) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-heading pb-3 mb-3">{{ __('lang.area') }} #2</div>
                    <div class="row">
                        <div class="col-sm-6 mb-4">
                            <label for="link-1-name" class="form-label">{{ __('lang.link_name') }} #1</label>
                            <input type="text" class="form-control" name="link-1-name" id="link-1-name"
                                value="{{ footerSettings('link_1_name', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-1-url" class="form-label">{{ __('lang.link_url') }} #1</label>
                            <input type="url" class="form-control" name="link-1-url" id="link-1-url"
                                value="{{ footerSettings('link_1_url', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-2-name" class="form-label">{{ __('lang.link_name') }} #2</label>
                            <input type="text" class="form-control" name="link-2-name" id="link-2-name"
                                value="{{ footerSettings('link_2_name', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-2-url" class="form-label">{{ __('lang.link_url') }} #2</label>
                            <input type="url" class="form-control" name="link-2-url" id="link-2-url"
                                value="{{ footerSettings('link_2_url', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-3-name" class="form-label">{{ __('lang.link_name') }} #3</label>
                            <input type="text" class="form-control" name="link-3-name" id="link-3-name"
                                value="{{ footerSettings('link_3_name', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-3-url" class="form-label">{{ __('lang.link_url') }} #3</label>
                            <input type="url" class="form-control" name="link-3-url" id="link-3-url"
                                value="{{ footerSettings('link_3_url', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4 mb-sm-0">
                            <label for="link-4-name" class="form-label">{{ __('lang.link_name') }} #4</label>
                            <input type="text" class="form-control" name="link-4-name" id="link-4-name"
                                value="{{ footerSettings('link_4_name', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6">
                            <label for="link-4-url" class="form-label">{{ __('lang.link_url') }} #4</label>
                            <input type="url" class="form-control" name="link-4-url" id="link-4-url"
                                value="{{ footerSettings('link_4_url', false) }}" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-heading pb-3 mb-3">{{ __('lang.area') }} #3</div>
                    <div class="row">
                        <div class="col-sm-6 mb-4">
                            <label for="link-5-name" class="form-label">{{ __('lang.link_name') }} #5</label>
                            <input type="text" class="form-control" name="link-5-name" id="link-5-name"
                                value="{{ footerSettings('link_5_name', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-5-url" class="form-label">{{ __('lang.link_url') }} #5</label>
                            <input type="url" class="form-control" name="link-5-url" id="link-5-url"
                                value="{{ footerSettings('link_5_url', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-6-name" class="form-label">{{ __('lang.link_name') }} #6</label>
                            <input type="text" class="form-control" name="link-6-name" id="link-6-name"
                                value="{{ footerSettings('link_6_name', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-6-url" class="form-label">{{ __('lang.link_url') }} #6</label>
                            <input type="url" class="form-control" name="link-6-url" id="link-6-url"
                                value="{{ footerSettings('link_6_url', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-7-name" class="form-label">{{ __('lang.link_name') }} #7</label>
                            <input type="text" class="form-control" name="link-7-name" id="link-7-name"
                                value="{{ footerSettings('link_7_name', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="link-7-url" class="form-label">{{ __('lang.link_url') }} #7</label>
                            <input type="url" class="form-control" name="link-7-url" id="link-7-url"
                                value="{{ footerSettings('link_7_url', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4 mb-sm-0">
                            <label for="link-8-name" class="form-label">{{ __('lang.link_name') }} #8</label>
                            <input type="text" class="form-control" name="link-8-name" id="link-8-name"
                                value="{{ footerSettings('link_8_name', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6">
                            <label for="link-8-url" class="form-label">{{ __('lang.link_url') }} #8</label>
                            <input type="url" class="form-control" name="link-8-url" id="link-8-url"
                                value="{{ footerSettings('link_8_url', false) }}" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="card-heading pb-3 mb-3">{{ __('lang.area') }} #4</div>
                    <div class="row">
                        <div class="col-sm-6 mb-4 mb-sm-0">
                            <label for="email" class="form-label">{{ __('lang.email') }}</label>
                            <input type="email" class="form-control" name="email" id="email"
                                value="{{ footerSettings('email', false) }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6">
                            <label for="location" class="form-label">{{ __('lang.location') }}</label>
                            <input type="text" class="form-control" name="location" id="location"
                                value="{{ footerSettings('location', false) }}" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-heading pb-3 mb-3">{{ __('lang.bottom_bar') }}</div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="copyright" class="form-label">{{ __('lang.copyright') }}</label>
                            <input type="text" class="form-control" name="copyright" id="copyright"
                                value="{{ footerSettings('copyright', false) }}" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>
@stop