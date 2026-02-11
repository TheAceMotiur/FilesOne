@extends("admin.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">
        <div class="overview-card card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <p class="count m-0" id="top-card-users"></p>
                        <p class="title m-0">{{ __('lang.users') }}</p>
                    </div>
                    <div class="col-4 d-flex">
                        <div class="icon ms-auto my-auto">
                            <i class="fa-solid fa-users fa-xl fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">
        <div class="overview-card card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <p class="count m-0" id="top-card-files"></p>
                        <p class="title m-0">{{ __('lang.files') }}</p>
                    </div>
                    <div class="col-4 d-flex">
                        <div class="icon ms-auto my-auto">
                            <i class="fa-solid fa-folder-open fa-xl fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">
        <div class="overview-card card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <p class="count m-0" id="top-card-file-types"></p>
                        <p class="title m-0">{{ __('lang.file_types') }}</p>
                    </div>
                    <div class="col-4 d-flex">
                        <div class="icon ms-auto my-auto">
                            <i class="fa-regular fa-file-lines fa-xl fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">
        <div class="overview-card card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <p class="count m-0" id="top-card-file-size"></p>
                        <p class="title m-0">{{ __('lang.file_size') }}</p>
                    </div>
                    <div class="col-4 d-flex">
                        <div class="icon ms-auto my-auto">
                            <i class="fa-solid fa-file-circle-question fa-xl fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">
        <div class="overview-card card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <p class="count m-0" id="top-card-posts"></p>
                        <p class="title m-0">{{ __('lang.posts') }}</p>
                    </div>
                    <div class="col-4 d-flex">
                        <div class="icon ms-auto my-auto">
                            <i class="fa-solid fa-align-center fa-xl fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">
        <div class="overview-card card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <p class="count m-0" id="top-card-comments"></p>
                        <p class="title m-0">{{ __('lang.comments') }}</p>
                    </div>
                    <div class="col-4 d-flex">
                        <div class="icon ms-auto my-auto">
                            <i class="fa-solid fa-comments fa-xl fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">
        <div class="overview-card card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <p class="count m-0" id="top-card-sales"></p>
                        <p class="title m-0">{{ __('lang.sales') }}</p>
                    </div>
                    <div class="col-4 d-flex">
                        <div class="icon ms-auto my-auto">
                            <i class="fa-solid fa-credit-card fa-xl fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">
        <div class="overview-card card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <p class="count m-0" id="top-card-revenue"></p>
                        <p class="title m-0">{{ __('lang.revenue') }}</p>
                    </div>
                    <div class="col-4 d-flex">
                        <div class="icon ms-auto my-auto">
                            <i class="fa-solid fa-money-bill fa-xl fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xxl-3 mb-4 mb-xxl-0">
        <div class="overview-card card h-100">
            <div class="card-body">
                <div 
                    id="chart-quota" 
                    data-url="{{ LaravelLocalization::localizeUrl('/admin/overview/quota-analytics') }}"></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xxl-3 mb-4 mb-xxl-0">
        <div class="overview-card card h-100">
            <div class="card-body">
                <div 
                    id="chart-types" 
                    data-url="{{ LaravelLocalization::localizeUrl('/admin/overview/file-types-analytics') }}"></div>
            </div>
        </div>
    </div>
    <div class="col-xxl-6">
        <div class="overview-card card h-100">
            <div class="card-body">
                <div 
                    id="chart-register" 
                    data-url="{{ LaravelLocalization::localizeUrl('/admin/overview/registration-analytics') }}"></div>
            </div>
        </div>
    </div>
</div>
@stop