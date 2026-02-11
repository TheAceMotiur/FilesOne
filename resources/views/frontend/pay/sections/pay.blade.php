<section class="pay-page">
    <div class="d-flex justify-content-center">
        <ul class="nav nav-pills d-none" id="payment-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button 
                    class="nav-link active" 
                    id="pay-details" 
                    data-bs-toggle="pill"
                    data-bs-target="#pills-details" 
                    type="button" 
                    role="tab" 
                    aria-controls="pills-details"
                    aria-selected="false">
                    {{ __('lang.details') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button 
                    class="nav-link" 
                    id="pay-payment" 
                    data-bs-toggle="pill"
                    data-bs-target="#pills-payment" 
                    type="button" 
                    role="tab" 
                    aria-controls="pills-payment"
                    aria-selected="false">
                    {{ __('lang.payment') }}
                </button>
            </li>
        </ul>
        <div class="width-sm">
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
            @if ($errors->any())
                <div class="alert alert-1 show mb-4" role="alert">
                    @foreach ($errors->all() as $error)
                        <p class="m-0">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="tab-content" id="payment-tabsContent">
                <div 
                    class="tab-pane fade show active" 
                    id="pills-details" 
                    role="tabpanel"
                    aria-labelledby="pay-details" 
                    tabindex="0">
                    <div class="payment-details card">
                        <div class="card-body p-5">
                            <h2 class="card-heading pb-3 mb-3">{{ __('lang.details') }}</h2>
                            <div class="d-flex justify-content-between mb-1">
                                <div>
                                    <p class="product-details-title m-0">{{ __('lang.plan_name') }}</p>
                                </div>
                                <div>
                                    <p class="product-details-text text-end m-0">{{ $plan->name }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <div>
                                    <p class="product-details-title m-0">{{ __('lang.start_date') }}</p>
                                </div>
                                <div>
                                    <p class="product-details-text text-end m-0">{{ $dates[0] }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <div>
                                    <p class="product-details-title m-0">{{ __('lang.end_date') }}</p>
                                </div>
                                <div>
                                    <p class="product-details-text text-end m-0">{{ $dates[1] }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <div>
                                    <p class="product-details-title m-0">{{ __('lang.duration') }}</p>
                                </div>
                                <div>
                                    <p class="product-details-text text-end m-0">
                                        {{ $period == 1 ? __('lang.monthly_non_recurring') : __('lang.yearly_non_recurring') }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <div>
                                    <p class="product-details-title m-0">{{ __('lang.total_price') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="product-details-text text-end d-flex m-0">
                                        <i class="fa-solid fa-tag fa-fw my-auto"></i>
                                        @if ($plan->free)
                                            {{ paymentSetting('currency_icon') . 0 }}
                                        @else
                                            {{ paymentSetting('currency_icon') . ($period == 1 ? $plan->price_monthly : $plan->price_yearly) }}  
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="payment-terms p-3">
                                <div class="form-check d-flex gap-2">
                                    <div class="my-auto">
                                        <input class="form-check-input" type="checkbox" value="1" id="pay-terms">
                                    </div>
                                    <div>
                                        <label class="form-check-label" for="pay-terms">
                                            @php
                                                $privacySlug = LaravelLocalization::localizeUrl(pageSlug('privacy_policy', true));
                                                $termsSlug = LaravelLocalization::localizeUrl(pageSlug('terms_of_use', true))
                                            @endphp
                                            {!!
                                                __(
                                                    'lang.accept_pay',
                                                    [
                                                        'url1' => $privacySlug,
                                                        'url2' => $termsSlug
                                                    ]
                                                )
                                            !!}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <div>
                                    <button class="payment-tabs btn btn-color-1" data-tab="pay-payment" disabled>
                                        <span class="pe-none me-1">{{ __('lang.payment') }}</span>
                                        <i class="fa-solid fa-arrow-right fa-fw pe-none"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div 
                    class="tab-pane fade" 
                    id="pills-payment" 
                    role="tabpanel" 
                    aria-labelledby="pay-payment" 
                    tabindex="0">
                    <div class="payment-methods card">
                        <div class="card-body p-5">
                            <h2 class="card-heading pb-3 mb-3">{{ __('lang.payment') }}</h2>
                            <p class="text-md mb-4">{{ __('lang.payment_info_plan') }}</p>
                            @if ($plan->free)
                                @if ($myPlan['free'])
                                    <p class="text-md fw-bold mb-4">{{ __('lang.already_free_plan') }}</p>
                                @else
                                    <p class="text-md fw-bold mb-4">{{ __('lang.cannot_downgrade_free_plan') }}</p>
                                @endif
                            @else
                                @if ($stripe->status == 1)
                                    <div class="payment-method">
                                        <div 
                                            class="form-check m-0" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#stripe-cc" 
                                            role="navigation"
                                            aria-expanded="false" 
                                            aria-controls="stripe-cc">
                                            <input class="form-check-input pe-none" type="checkbox"
                                                name="payment-method" id="pay-with-stripe">
                                            <label class="form-check-label pe-none ms-2" for="pay-with-stripe">
                                                {{ __('lang.pay_stripe') }}
                                            </label>
                                        </div>
                                        <div class="payment-method-collapse collapse" id="stripe-cc" data-token="{{ url()->full() }}/stripe-token">
                                            <div class="px-3 pb-3">
                                                <form id="stripe-payment">
                                                    <div class="d-flex py-5">
                                                        <span class="spinner-border m-auto" role="status"></span>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($razorpay->status == 1)
                                    <div class="payment-method mt-2">
                                        <div 
                                            class="form-check m-0" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#razorpay-cc" 
                                            role="navigation"
                                            aria-expanded="false" 
                                            aria-controls="razorpay-cc">
                                            <input class="form-check-input pe-none" type="checkbox"
                                                name="payment-method" id="pay-with-razorpay">
                                            <label class="form-check-label pe-none ms-2" for="pay-with-razorpay">
                                                {{ __('lang.pay_razorpay') }}
                                            </label>
                                        </div>
                                        <div class="payment-method-collapse collapse" id="razorpay-cc" data-token="{{ url()->full() }}/razorpay-token">
                                            <div class="p-3">
                                                <form id="razorpay-payment">
                                                    <div>
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-color-2 w-100 disabled" 
                                                            data-name="{{ $plan->name }}"
                                                            data-price="{{ $period == 1 ? $plan->price_monthly : $plan->price_yearly }}" 
                                                            data-currency="{{ strtoupper($currency) }}"
                                                            data-order="" disabled>{{ __('lang.pay_now') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($bank->status == 1)
                                    <div class="payment-method mt-2">
                                        <div 
                                            class="form-check m-0" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#bank" 
                                            role="navigation"
                                            aria-expanded="false" 
                                            aria-controls="bank">
                                            <input class="form-check-input pe-none" type="checkbox"
                                                name="payment-method" id="pay-with-bank">
                                            <label class="form-check-label pe-none ms-2" for="pay-with-bank">
                                                {{ __('lang.pay_bank') }}
                                            </label>
                                        </div>
                                        <div class="payment-method-collapse collapse" id="bank">
                                            <div class="px-3 pb-3">
                                                <form action="{{ url()->full() }}/bank" method="post" name="bank-payment">
                                                    <div class="row">
                                                        <div>
                                                            <p class="text-md m-0">
                                                                {!! $bank->info !!}
                                                            </p>
                                                        </div>
                                                        <div class="mt-3">
                                                            <textarea 
                                                                class="form-control" 
                                                                rows="3" 
                                                                name="bank-info" 
                                                                placeholder="{{ __('lang.bank_placeholder') }}"></textarea>
                                                        </div>
                                                        <div class="mt-3">
                                                            <button type="submit"
                                                                class="btn btn-color-2 w-100">
                                                                <span>{{ __('lang.confirm_payment') }}</span>
                                                            </button>
                                                        </div>
                                                        @csrf
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <div class="mt-4">
                                <button class="payment-tabs btn btn-color-1" data-tab="pay-details">
                                    <i class="fa-solid fa-arrow-left fa-fw pe-none"></i>
                                    <span class="pe-none ms-1">{{ __('lang.details') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>