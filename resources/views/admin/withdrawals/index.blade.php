@extends("admin.layouts.dashboard")
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
                    class="withdrawals-table table-init" 
                    data-url="{{ LaravelLocalization::localizeUrl('/admin/withdrawals') }}" 
                    data-columns="Date,User,Amount,Gateway,Status,Action" 
                    data-search="true"></div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-content offcanvas-end" tabindex="-1" id="withdrawal-details">
    <div class="offcanvas-header">
        <button type="button" class="action-button btn btn-sm" data-bg="white" data-bs-dismiss="offcanvas"
            aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="detail-card p-3 mt-3">
            <p class="title">{{ __('lang.withdrawal_info') }}</p>
            <p class="content d-flex mb-2">
                {{ __('lang.gateway') }}:<span class="ms-2" id="gateway"></span>
            </p>
            <p class="content d-flex mb-2">
                {{ __('lang.amount') }}:<span class="ms-2" id="amount"></span>
            </p>
            <p class="content d-flex mb-2">
                {{ __('lang.info') }}:<span class="ms-2" id="info"></span>
            </p>
            <p class="content d-flex m-0">
                IP:<span class="ms-2" id="user-ip"></span>
            </p>
        </div>
    </div>
</div>
<form method="POST" action="#" class="verify-modal-form">
    <div class="modal fade" id="verify-modal" tabindex="-1" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="verify-icon fa-solid fa-triangle-exclamation fa-4x mb-3"></i>
                    <h4>{{ __('lang.modal_question') }}</h4>
                    <p class="modal-text m-0">{{ __('lang.verify_withdrawal') }}</p>
                    @csrf
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-color-4" data-bs-dismiss="modal">{{ __('lang.cancel') }}</button>
                    <button type="submit" class="btn btn-color-2 verify-row-modal" data-url="">{{ __('lang.verify') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/withdrawals/delete') }}" class="delete-modal-form">
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-4x mb-3"></i>
                    <h4>{{ __('lang.modal_question') }}</h4>
                    <p class="modal-text m-0">{{ __('lang.withdrawal_reject') }}</p>
                    @csrf
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-color-4" data-bs-dismiss="modal">{{ __('lang.cancel') }}</button>
                    <button type="submit" class="btn btn-color-1 delete-row-modal" data-url="">{{ __('lang.reject') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
@stop