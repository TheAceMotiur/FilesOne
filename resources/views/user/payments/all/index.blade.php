@extends("user.layouts.dashboard")
@section("content")
<div class="row">
    <div>
        <div class="card">
            <div class="card-body">
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
                <div 
                    class="payments-table table-init" 
                    data-url="{{ LaravelLocalization::localizeUrl('/user/payments/all') }}"
                    data-columns="Date,Plan,Duration,Gateway,Status,Action" 
                    data-search="true"></div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-content offcanvas-end" tabindex="-1" id="log-details">
    <div class="offcanvas-header">
        <button type="button" class="action-button btn btn-sm" data-bg="white" data-bs-dismiss="offcanvas"
            aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="payment-detail text-center">
            <p class="plan-price m-0" data-monthly="29" data-yearly="310" data-currency="$">
                <span id="price"></span>
                <span>/</span>
                <span id="period"></span>
            </p>
            <p class="plan-name m-0" id="plan-name"></p>
        </div>
        <div class="detail-card p-3 mt-3">
            <p class="title">{{ __('lang.payment_info') }}</p>
            <p class="content d-flex mb-2">
                {{ __('lang.gateway') }}:<span class="ms-2" id="gateway"></span>
            </p>
            <p class="content d-flex mb-2">
                {{ __('lang.transaction') }}:<span class="ms-2" id="transaction"></span>
            </p>
            <p class="content d-flex mb-2">
                {{ __('lang.info') }}:<span class="ms-2" id="info"></span>
            </p>
            <p class="content d-flex m-0">
                IP:<span class="ms-2" id="user-ip"></span>
            </p>
        </div>
        <div class="detail-card p-3 mt-3">
            <p class="title">{{ __('lang.features') }}</p>
            <p class="content d-flex mb-2">
                {{ __('lang.disk') }}:<span class="ms-2" id="disk"></span>
            </p>
            <p class="content d-flex mb-2">
                {{ __('lang.formats') }}:<span class="ms-2" id="formats"></span>
            </p>
            <p class="content d-flex mb-2">
                {{ __('lang.auto_deletion') }}:<span class="ms-2" id="auto-deletion"></span>
            </p>
            <p class="content d-flex mb-2">
                {{ __('lang.skip_countdown') }}:<span class="ms-2" id="countdown"></span>
            </p>
            <p class="content d-flex m-0">
                API:<span class="ms-2" id="api"></span>
            </p>
        </div>
    </div>
</div>
@stop