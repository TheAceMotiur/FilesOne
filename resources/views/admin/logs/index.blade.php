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
                    class="logs-table table-init" 
                    data-url="{{ LaravelLocalization::localizeUrl('/admin/logs') }}" 
                    data-columns="Date,User,IP,OS,Browser,Action" 
                    data-search="true"
                    data-buttons="log"></div>
            </div>
        </div>
    </div>
</div>
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl('/admin/logs/clear') }}" 
    class="delete-modal-form">
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-4x mb-3"></i>
                    <h4>{{ __('lang.modal_question') }}</h4>
                    <p class="modal-text m-0">{{ __('lang.modal_text') }}</p>
                    @csrf
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-color-4" data-bs-dismiss="modal">{{ __('lang.cancel') }}</button>
                    <button type="submit" class="btn btn-color-1 delete-row-modal" data-url="">{{ __('lang.delete') }}</button>
                </div>
            </div>
        </div>
    </div>
</form>
@stop