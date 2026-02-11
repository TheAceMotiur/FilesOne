@extends("user.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-md-6 col-xl-4">
        <div class="plan-card card">
            <div class="card-body">
                <i class="current-icon fa-regular fa-circle-check fa-2x position-absolute"></i>
                <h3 class="plan-name mb-3">{{ $plan['name'] }}</h3>
                <p class="plan-price">
                    <span class="price">
                        @if ($plan['free'])
                            {{ paymentSetting('currency_icon') . '0' }}
                        @else
                            {{ paymentSetting('currency_icon') . $plan['price'] }}
                        @endif
                    </span>
                    /
                    <span class="period text-md">
                        {{ $plan['period'] == 1 ? __('lang.monthly') : __('lang.yearly') }}
                    </span>
                </p>
                <ul class="plan-features list-group list-unstyled my-4">
                    <li class="list-group mb-2">
                        <span>
                            <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                            {{ __('lang.uploadable_formats') }}; {{ count($plan['features']['formats']) }}
                            <i 
                                class="form-help fa-solid fa-circle-question ms-1" 
                                data-bs-container="body"
                                data-bs-toggle="popover" 
                                data-bs-placement="bottom" 
                                data-bs-content="{{ strtoupper(implode(', ',$plan['features']['formats'])) }}"></i>
                        </span>
                    </li>
                    <li class="list-group mb-2">
                        <span>
                            <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                            {{ __('lang.total_disk') }}; {{ formatMegaBytes($plan['features']['disk']) }}
                        </span>
                    </li>
                    <li class="list-group mb-2">
                        <span>
                            <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                            {{ __('lang.auto_deletion') }}; {{ $plan['features']['auto_deletion'] == 0 ? __('lang.never') : $plan['features']['auto_deletion'] . ' ' . __('lang.days') }}
                        </span>
                    </li>
                    @if (isset($plan['features']['countdown']) && $plan['features']['countdown'] == 1)
                        <li class="list-group mb-2">
                            <span>
                                <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                {{ __('lang.skip_countdown') }}
                            </span>
                        </li>
                    @endif
                    <li class="list-group">
                        @if ($plan['free'])
                            <span>
                                <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                {{ __('lang.standard_support') }}
                            </span>
                        @else
                            <span>
                                <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                {{ __('lang.priority_support') }}
                            </span>
                        @endif
                    </li>
                    @if (isset($plan['features']['api']) && $plan['features']['api'] == 1)
                        <li class="list-group mt-2">
                            <span>
                                <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                API
                            </span>
                        </li>
                    @endif
                </ul>
                <div>
                    <a 
                        href="{{ LaravelLocalization::localizeUrl(pageSlug('pricing', true)) }}" 
                        class="btn btn-color-1">{{ __('lang.change_plan') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop