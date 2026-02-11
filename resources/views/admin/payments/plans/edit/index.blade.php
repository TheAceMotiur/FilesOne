@extends("admin.layouts.dashboard")
@section("content")
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl("/admin/payments/plans/edit/{$plan['id']}") }}">
    <div class="row">
        <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="setting-tabs nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a href="?tab=basic" class="nav-link active" id="v-pills-basic-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-basic" role="tab" aria-controls="v-pills-basic" aria-selected="true">{{ __('lang.basic_settings') }}</a>
                        <a href="?tab=permissions" class="nav-link" id="v-pills-permissions-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-permissions" role="tab" aria-controls="v-pills-permissions" aria-selected="false">{{ __('lang.permissions') }}</a>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-color-1 w-100">
                            {{ __('lang.update') }}
                        </button>
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
                <div class="tab-pane fade show active" id="v-pills-basic" role="tabpanel" aria-labelledby="v-pills-basic-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.basic_settings') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="name" class="form-label">{{ __('lang.name') }}</label>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $plan['name'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="status" class="form-label">{{ __('lang.status') }}</label>
                                    <select class="form-select" name="status" id="status" aria-label="Status"{{ $plan['id'] == 1 ? ' disabled' : '' }}>
                                        @if ($plan['id'] == 1)
                                            <option value="1" selected>{{ __('lang.enable') }}</option>
                                        @else
                                            <option value="1"{{ $plan['status'] == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                            <option value="0"{{ $plan['status'] == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4 mb-sm-0 mb-lg-0 mb-md-4">
                                    <label for="price-monthly" class="form-label">{{ __('lang.monthly_price') }}</label>
                                    <input type="{{ $plan['id'] == 1 ? 'text' : 'number' }}" name="price-monthly" class="form-control" id="price-monthly" value="{{ $plan['id'] == 1 ? __('free') : $plan['price_monthly'] }}" autocomplete="off"{{ $plan['id'] == 1 ? ' disabled' : '' }}>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6">
                                    <label for="price-yearly" class="form-label">{{ __('lang.yearly_price') }}</label>
                                    <input type="{{ $plan['id'] == 1 ? 'text' : 'number' }}" name="price-yearly" class="form-control" id="price-yearly" value="{{ $plan['id'] == 1 ? __('free') : $plan['price_yearly'] }}" autocomplete="off"{{ $plan['id'] == 1 ? ' disabled' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-permissions" role="tabpanel" aria-labelledby="v-pills-permissions-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.permissions') }}</div>
                            <div class="row row-cols-2 row-cols-sm-4 row-cols-lg-6 gy-3 mb-4">
                                @foreach (uploadableTypes('array') as $type)
                                    <div class="col">
                                        <div class="form-check">
                                            <input 
                                                class="form-check-input position-relative" 
                                                type="checkbox" 
                                                name="formats[]" 
                                                id="{{ $type }}" 
                                                value="{{ $type }}"
                                                {{ in_array($type, $features['formats']) ? ' checked' : '' }}>
                                            <label class="form-check-label ms-2" for="{{ $type }}">
                                                {{ strtoupper($type) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="disk" class="form-label">{{ __('lang.disk') }} (MB)</label>
                                    <input type="number" name="disk" class="form-control" id="disk" value="{{ $features['disk'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="auto-deletion" class="form-label">{{ __('lang.auto_deletion_days') }}</label>
                                    <select class="form-select" name="auto-deletion" id="auto-deletion" aria-label="Auto deletion">
                                        <option value="0"{{ $features['auto_deletion'] == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                        <option value="1"{{ $features['auto_deletion'] == 1 ? ' selected' : '' }}>1 {{ __('lang.day') }}</option>
                                        <option value="7"{{ $features['auto_deletion'] == 7 ? ' selected' : '' }}>7 {{ __('lang.days') }}</option>
                                        <option value="30"{{ $features['auto_deletion'] == 30 ? ' selected' : '' }}>30 {{ __('lang.days') }}</option>
                                        <option value="90"{{ $features['auto_deletion'] == 90 ? ' selected' : '' }}>90 {{ __('lang.days') }}</option>
                                        <option value="180"{{ $features['auto_deletion'] == 180 ? ' selected' : '' }}>180 {{ __('lang.days') }}</option>
                                        <option value="360"{{ $features['auto_deletion'] == 360 ? ' selected' : '' }}>360 {{ __('lang.days') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4 mb-sm-0 mb-lg-0 mb-md-4">
                                    <label for="api" class="form-label">API</label>
                                    <select class="form-select" name="api" id="api" aria-label="Api">
                                        <option value="1"{{ $features['api'] == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ $features['api'] == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6">
                                    <label for="countdown" class="form-label text-capitalize">{{ __('lang.skip_countdown') }}</label>
                                    <select class="form-select" name="countdown" id="countdown" aria-label="Skip countdown">
                                        <option value="1"{{ $features['countdown'] == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ $features['countdown'] == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>
@stop