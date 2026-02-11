@extends("admin.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
        <div class="card">
            <div class="card-body">
                <div 
                    class="setting-tabs nav flex-column nav-pills" 
                    id="v-pills-tab" 
                    role="tablist" 
                    aria-orientation="vertical">
                    <a 
                        href="?tab=settings" 
                        class="nav-link active" 
                        id="v-pills-settings-tab" 
                        data-bs-toggle="pill" 
                        data-bs-target="#v-pills-settings" 
                        role="tab" 
                        aria-controls="v-pills-settings" 
                        aria-selected="true">{{ __('lang.language_settings') }}</a>
                    <a 
                        href="?tab=languages" 
                        class="nav-link" 
                        id="v-pills-languages-tab" 
                        data-bs-toggle="pill" 
                        data-bs-target="#v-pills-languages" 
                        role="tab" 
                        aria-controls="v-pills-languages" 
                        aria-selected="false">{{ __('lang.all_languages') }}</a>
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
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/language') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.language_settings') }}</div>
                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <label for="base-language" class="form-label">{{ __('lang.base_language') }}</label>
                                    <select class="form-select" name="base-language" id="base-language" aria-label="Base Language">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language['code'] }}"{{ langSetting('base_language') == $language['code'] ? ' selected' : '' }}>
                                                {{ $language['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label for="language-switcher" class="form-label">{{ __('lang.language_switcher') }}</label>
                                    <select class="form-select" name="language-switcher" id="language-switcher" aria-label="Language Switcher">
                                        <option value="1"{{ langSetting('language_switcher') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ langSetting('language_switcher') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
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
            <div class="tab-pane fade" id="v-pills-languages" role="tabpanel" aria-labelledby="v-pills-languages-tab" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading pb-3 mb-3">{{ __('lang.all_languages') }}</div>
                        <div 
                            class="language-table" 
                            data-url="{{ LaravelLocalization::localizeUrl('/admin/language/all') }}" 
                            data-columns="Name,Code,Direction" 
                            data-search="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop