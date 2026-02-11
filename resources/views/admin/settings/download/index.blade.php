@extends("admin.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
        <div class="card">
            <div class="card-body">
                <div class="setting-tabs nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a href="?tab=settings" class="nav-link active" id="v-pills-settings-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="true">{{ __('lang.settings') }}</a>
                    <a href="?tab=ads" class="nav-link" id="v-pills-ads-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-ads" role="tab" aria-controls="v-pills-ads" aria-selected="false">{{ __('lang.ads') }}</a>
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
            <div class="tab-pane fade show active" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/download') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.download_settings') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="countdown" class="form-label">{{ __('lang.countdown_seconds') }}</label>
                                    <input type="text" class="form-control" name="countdown" id="countdown" value="{{ downloadSetting('countdown') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="adblock-blocker" class="form-label">{{ __('lang.adblock_blocker') }}</label>
                                    <select class="form-select" name="adblock-blocker" id="adblock-blocker" aria-label="Adblock Blocker">
                                        <option value="1"{{ downloadSetting('adblock_blocker') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ downloadSetting('adblock_blocker') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
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
            <div class="tab-pane fade" id="v-pills-ads" role="tabpanel" aria-labelledby="v-pills-ads-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/download/ads') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.ad_settings') }}</div>
                            <div class="row">
                                <div class="mb-4">
                                    <label for="top-area" class="form-label">{{ __('lang.top_area') }}</label>
                                    <textarea class="form-control" name="top-area" id="top-area" rows="3" 
                                        autocomplete="off">{{ downloadSetting('top_area') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="middle-area" class="form-label">{{ __('lang.middle_area') }}</label>
                                    <textarea class="form-control" name="middle-area" id="middle-area" rows="3" 
                                        autocomplete="off">{{ downloadSetting('middle_area') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="bottom-area" class="form-label">{{ __('lang.bottom_area') }}</label>
                                    <textarea class="form-control" name="bottom-area" id="bottom-area" rows="3" 
                                        autocomplete="off">{{ downloadSetting('bottom_area') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="js-codes" class="form-label">{{ __('lang.js_codes') }}</label>
                                    <textarea class="form-control" name="js-codes" id="js-codes" rows="3" 
                                        autocomplete="off">{{ downloadSetting('js_codes') }}</textarea>
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
        </div>
    </div>
</div>
@stop