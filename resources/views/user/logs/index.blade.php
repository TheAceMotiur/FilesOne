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
                    class="logs-table table-init" 
                    data-url="{{ LaravelLocalization::localizeUrl('/user/logs') }}" 
                    data-columns="Date,IP,OS,Browser,Action" 
                    data-search="true"></div>
            </div>
        </div>
    </div>
</div>
@stop