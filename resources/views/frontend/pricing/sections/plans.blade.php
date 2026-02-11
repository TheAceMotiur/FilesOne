<section class="pricing-plans-area">
    <div class="container">
        <div class="row gx-5 mb-5">
            @if (widget('pricing','plans','upper_title') || widget('pricing','plans','title'))
                <div class="col-lg-6 me-auto mb-4">
                    <div class="section-heading">
                        @if (widget('pricing','plans','upper_title'))
                            <p class="section-heading-upper animate animate__fadeIn">
                                {{ widget('pricing','plans','upper_title') }}
                            </p>
                        @endif
                        @if (widget('pricing','plans','title'))
                            <h2 class="position-relative pb-5 mb-0 animate animate__fadeIn" data-anm-delay="400ms">
                                {{ widget('pricing','plans','title') }}
                            </h2>
                        @endif
                    </div>
                </div>
            @endif
            <div class="d-flex flex-column flex-md-row justify-content-md-between gap-5 gap-md-0 align-items-center">
                @if (widget('pricing','plans','text'))
                    <p class="section-heading-text mb-0 animate animate__fadeIn" data-anm-delay="800ms">
                        {{ widget('pricing','plans','text') }}
                    </p>
                @endif
                <label class="switcher-form position-relative text-center" for="plans-inner-card-period">
                    <input type="checkbox" id="plans-inner-card-period" value="1" data-auth="{{ auth()->check() ? 'true' : 'false' }}">
                    <span class="slider position-absolute">
                        <span class="position-absolute">{{ __('lang.monthly') }}</span>
                        <span class="position-absolute">{{ __('lang.yearly') }}</span>
                    </span>
                </label>
            </div>
        </div>
        <div class="row row-gap-5 gx-5">
            @foreach ($plans as $plan)
                <div class="col-md-6 col-lg-4">
                    <div class="plan-card card h-100">
                        <div class="card-body">
                            <h3 class="plan-name mb-3">{{ $plan->name }}</h3>
                            <p 
                                class="plan-price" 
                                data-currency="{{ paymentSetting('currency_icon') }}" 
                                data-monthly="{{ isset($plan->price_monthly) ? $plan->price_monthly : 0 }}" 
                                data-monthly-string="{{ __('lang.monthly') }}" 
                                @if (Auth::check())
                                    data-monthly-url="{{ LaravelLocalization::localizeUrl(pageSlug('pay', true) . "/" .encrypt("plan={$plan->id}&period=1")) }}"
                                @else
                                    data-monthly-url="{{ LaravelLocalization::localizeUrl(pageSlug('login', true)) }}" 
                                @endif
                                data-yearly="{{ isset($plan->price_yearly) ? $plan->price_yearly : 0 }}" 
                                data-yearly-string="{{ __('lang.yearly') }}" 
                                @if (Auth::check())
                                    data-yearly-url="{{ LaravelLocalization::localizeUrl(pageSlug('pay', true) . "/" .encrypt("plan={$plan->id}&period=12")) }}">
                                @else
                                    data-yearly-url="{{ LaravelLocalization::localizeUrl(pageSlug('login', true)) }}">
                                @endif
                                <span class="price">
                                    {{ paymentSetting('currency_icon') . (isset($plan->price_monthly) ? $plan->price_monthly : 0) }}
                                </span>
                                /
                                <span class="period text-md">
                                    {{ __('lang.monthly') }}
                                </span>
                            </p>
                                @if (Auth::check())
                                    @if (isset($plan->free) && $plan->free == 1)
                                        <a 
                                        href="{{ LaravelLocalization::localizeUrl(pageSlug('pay', true) . "/" .encrypt("plan={$plan->id}&period=1")) }}" 
                                            class="btn btn-color-1 w-100">
                                            {{ __('lang.get_started') }}
                                        </a>
                                    @else
                                        <a 
                                            href="{{ LaravelLocalization::localizeUrl(pageSlug('pay', true) . "/" .encrypt("plan={$plan->id}&period=1")) }}" 
                                            class="btn btn-color-1 w-100">
                                            {{ __('lang.get_started') }}
                                        </a>
                                    @endif
                                @else
                                    <a 
                                        href="{{ LaravelLocalization::localizeUrl(pageSlug('login', true)) }}" 
                                        class="btn btn-color-1 w-100">
                                        {{ __('lang.get_started') }}
                                    </a>
                                @endif
                            @php
                                $features = json_decode($plan->features, true);  
                            @endphp
                            <ul class="plan-features list-group list-unstyled mt-4">
                                <li class="list-group mb-2">
                                    <span>
                                        <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                        {{ __('lang.uploadable_formats') }}; {{ count($features['formats']) }}
                                        <i 
                                            class="form-help fa-solid fa-circle-question ms-1" 
                                            data-bs-container="body"
                                            data-bs-toggle="popover" 
                                            data-bs-placement="bottom" 
                                            data-bs-content="{{ strtoupper(implode(', ',$features['formats'])) }}"></i>
                                    </span>
                                </li>
                                <li class="list-group mb-2">
                                    <span>
                                        <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                        {{ __('lang.total_disk') }}; {{ formatMegaBytes($features['disk']) }}
                                    </span>
                                </li>
                                <li class="list-group mb-2">
                                    <span>
                                        <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                        {{ __('lang.auto_deletion') }}; {{ $features['auto_deletion'] == 0 ? __('lang.never') : $features['auto_deletion'] . ' ' . __('lang.days') }}
                                    </span>
                                </li>
                                @if (isset($features['countdown']) && $features['countdown'] == 1)
                                    <li class="list-group mb-2">
                                        <span>
                                            <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                            {{ __('lang.skip_countdown') }}
                                        </span>
                                    </li>
                                @endif
                                <li class="list-group">
                                    <span>
                                        <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                        @if (isset($plan->free) && $plan->free == 1)
                                            {{ __('lang.standard_support') }}
                                        @else
                                            {{ __('lang.priority_support') }}
                                        @endif
                                    </span>
                                </li>
                                @if (isset($features['api']) && $features['api'] == 1)
                                    <li class="list-group mt-2">
                                        <span>
                                            <i class="fa-solid fa-circle-check fa-fw fa-lg me-1"></i>
                                            API
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>