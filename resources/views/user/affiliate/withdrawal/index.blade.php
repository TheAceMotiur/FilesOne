@extends("user.layouts.dashboard")
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
                        href="?tab=request" 
                        class="nav-link active" 
                        id="v-pills-request-tab" 
                        data-bs-toggle="pill" 
                        data-bs-target="#v-pills-request" 
                        role="tab" aria-controls="v-pills-request" 
                        aria-selected="true">{{ __('lang.request_withdrawal') }}</a>
                    <a 
                        href="?tab=withdrawal" 
                        class="nav-link" 
                        id="v-pills-withdrawal-tab" 
                        data-bs-toggle="pill" 
                        data-bs-target="#v-pills-withdrawal" 
                        role="tab" aria-controls="v-pills-withdrawal" 
                        aria-selected="false">{{ __('lang.withdrawal') }}</a>
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
                <button 
                    type="button" 
                    class="btn-close" 
                    data-bs-dismiss="alert" 
                    aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-2 alert-dismissible fade show" role="alert">
                <p class="m-0">{{ session('success') }}</p>
                <button 
                    type="button" 
                    class="btn-close" 
                    data-bs-dismiss="alert" 
                    aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-1 alert-dismissible fade show" role="alert">
                <p class="m-0">{{ session('error') }}</p>
                <button 
                    type="button" 
                    class="btn-close" 
                    data-bs-dismiss="alert" 
                    aria-label="Close"></button>
            </div>
        @endif
        <div class="tab-content" id="v-pills-tabContent">
            <div 
                class="tab-pane fade show active" 
                id="v-pills-request" 
                role="tabpanel" 
                aria-labelledby="v-pills-request-tab" 
                tabindex="0">
                <form 
                    method="POST" 
                    action="{{ LaravelLocalization::localizeUrl('/user/affiliate/withdrawal/request') }}" 
                    enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.request_withdrawal') }}</div>
                            <div class="bg-container-2 mb-4 p-3">
                                <p class="m-0">{{ __('lang.your_total_balance') }}: {{ paymentSetting('currency_icon') }}<span id="total-balance"></span></p>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="amount" class="form-label">{{ __('lang.amount') }}</label>
                                    <input type="number" name="amount" class="form-control" id="amount" 
                                        value="{{ old('amount') }}" step=".01" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="withdrawal-method" class="form-label">{{ __('lang.method') }}</label>
                                    <select class="form-select" name="method" id="withdrawal-method" aria-label="method">
                                        <option value="" selected>Select</option>
                                        @foreach ($methods as $method)
                                            <option value="{{ $method->id }}" data-desc="{{ $method->description }}">
                                                {{ $method->name . ' (Min: ' . paymentSetting('currency_icon') .  $method->minimum . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4 d-none" id="withdrawal-description">
                                    <p class="bg-container p-3 m-0">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                </div>
                                <div class="mb-4">
                                    <label for="details" class="form-label">{{ __('lang.details') }}</label>
                                    <textarea class="form-control" name="details" id="details" rows="3" 
                                        autocomplete="off">{{ old('details') }}</textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.submit') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div 
                class="tab-pane fade" 
                id="v-pills-withdrawal" 
                role="tabpanel" 
                aria-labelledby="v-pills-withdrawal-tab" 
                tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading pb-3 mb-3">{{ __('lang.withdrawal') }}</div>
                        <div 
                            class="withdrawal-table" 
                            data-url="{{ LaravelLocalization::localizeUrl('/user/affiliate/withdrawal') }}" 
                            data-columns="Date,Amount,Gateway,Status,Action" 
                            data-search="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form method="POST" action="{{ LaravelLocalization::localizeUrl('/user/affiliate/withdrawal/delete') }}" class="delete-modal-form">
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-4x mb-3"></i>
                    <h4>{{ __('lang.modal_question') }}</h4>
                    <p class="modal-text m-0">{{ __('lang.withdrawal_cancel') }}</p>
                    @csrf
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-color-4" data-bs-dismiss="modal">{{ __('lang.close') }}</button>
                    <button type="submit" class="btn btn-color-1 delete-row-modal" data-url="">{{ __('lang.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
@stop