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
                <div class="files-overview-card row mb-4">
                    <div class="col-md-4">
                        <div class="overview-card card">
                            <div class="card-body d-flex justify-content-between">
                                <p class="title m-0">{{ __('lang.total_revenue') }}</p>
                                <p class="count m-0">{{ paymentSetting('currency_icon') }}<span id="total-revenue"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div 
                    class="statistics-table table-init" 
                    data-url="{{ LaravelLocalization::localizeUrl('/user/affiliate/statistics') }}" 
                    data-columns="Date,File,Country,Revenue" 
                    data-search="true"></div>
            </div>
        </div>
    </div>
</div>
@stop