@extends("admin.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
        <div class="card">
            <div class="card-body">
                <div class="setting-tabs nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a href="?tab=send-email" class="nav-link active" id="v-pills-email-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-email" role="tab" aria-controls="v-pills-email" aria-selected="true">{{ __('lang.send_email') }}</a>
                    <a href="?tab=subscribers" class="nav-link" id="v-pills-subscribers-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-subscribers" role="tab" aria-controls="v-pills-subscribers" aria-selected="false">{{ __('lang.subscribers') }}</a>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-email" role="tabpanel" aria-labelledby="v-pills-email-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/subscribers') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.send_email_subscribers') }}</div>
                            <div class="row">
                                <div class="mb-4">
                                    <label for="subject" class="form-label">{{ __('lang.subject') }}</label>
                                    <input type="text" class="form-control" name="subject" id="subject" 
                                        value="{{ old('subject') }}" autocomplete="off">
                                </div>
                                <div class="mb-4">
                                    <label for="message" class="form-label">{{ __('lang.message') }}</label>
                                    <textarea class="form-control" name="message" id="message" rows="3" 
                                        autocomplete="off">{{ old('message') }}</textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-envelope me-1"></i>
                                        {{ __('lang.send_email') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-subscribers" role="tabpanel" aria-labelledby="v-pills-subscribers-tab" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading pb-3 mb-3">{{ __('lang.subscribers') }}</div>
                        <div 
                            class="subscribers-table" 
                            data-url="{{ LaravelLocalization::localizeUrl('/admin/subscribers/all') }}" 
                            data-columns="Email,Date,Verified,Action" 
                            data-search="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl('/admin/subscribers/delete') }}" 
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