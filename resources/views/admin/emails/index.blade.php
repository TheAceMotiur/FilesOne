@extends("admin.layouts.dashboard")
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
                        href="?tab=settings" 
                        class="nav-link active" 
                        id="v-pills-settings-tab" 
                        data-bs-toggle="pill" 
                        data-bs-target="#v-pills-settings" 
                        role="tab" aria-controls="v-pills-settings" 
                        aria-selected="true">{{ __('lang.email_settings') }}</a>
                    <a 
                        href="?tab=contents" 
                        class="nav-link" 
                        id="v-pills-contents-tab" 
                        data-bs-toggle="pill" 
                        data-bs-target="#v-pills-contents" 
                        role="tab" aria-controls="v-pills-contents" 
                        aria-selected="false">{{ __('lang.email_contents') }}</a>
                    <a 
                        href="?tab=emails" 
                        class="nav-link" 
                        id="v-pills-emails-tab" 
                        data-bs-toggle="pill" 
                        data-bs-target="#v-pills-emails" 
                        role="tab" aria-controls="v-pills-emails" 
                        aria-selected="false">{{ __('lang.contact_emails') }}</a>
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
                id="v-pills-settings" 
                role="tabpanel" 
                aria-labelledby="v-pills-settings-tab" 
                tabindex="0">
                <form 
                    method="POST" 
                    action="{{ LaravelLocalization::localizeUrl('/admin/emails') }}" 
                    enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.email_settings') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="email" class="form-label">{{ __('lang.receiver_email') }}</label>
                                    <input type="email" class="form-control" name="email" id="email" value="{{ emailSetting('email') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="email-noreply" class="form-label">{{ __('lang.noreply_email') }}</label>
                                    <input type="email" class="form-control" name="email-noreply" id="email-noreply" value="{{ emailSetting('email_noreply') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="email-logo" class="form-label">{{ __('lang.email_logo') }}</label>
                                    <input type="file" class="form-control" name="email-logo" id="email-logo">
                                    @if (emailSetting('email_logo'))
                                        <div class="img-container d-flex p-3 mt-4">
                                            <div class="covered" style="background: url({{ img('other', emailSetting('email_logo')) }});"></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="smtp-host" class="form-label">{{ __('lang.smtp_host') }}</label>
                                    <input type="text" class="form-control" name="smtp-host" id="smtp-host" value="{{ emailSetting('smtp_host') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="smtp-port" class="form-label">{{ __('lang.smtp_port') }}</label>
                                    <input type="number" class="form-control" name="smtp-port" id="smtp-port" value="{{ emailSetting('smtp_port') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="smtp-encryption" class="form-label">{{ __('lang.encryption') }}</label>
                                    <select class="form-select" name="smtp-encryption" id="smtp-encryption">
                                        <option value="none"{{ emailSetting('smtp_encryption') == 'none' ? ' selected' : '' }}>{{ __('lang.none') }}</option>
                                        <option value="tls"{{ emailSetting('smtp_encryption') == 'tls' ? ' selected' : '' }}>TLS</option>
                                        <option value="ssl"{{ emailSetting('smtp_encryption') == 'ssl' ? ' selected' : '' }}>SSL</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="smtp-user" class="form-label">{{ __('lang.smtp_user') }}</label>
                                    <input type="text" class="form-control" name="smtp-user" id="smtp-user" value="{{ emailSetting('smtp_user') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="smtp-password" class="form-label">{{ __('lang.smtp_password') }}</label>
                                    <input type="text" class="form-control" name="smtp-password" id="smtp-password" value="{{ emailSetting('smtp_password') }}" autocomplete="off">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-contents" role="tabpanel" aria-labelledby="v-pills-contents-tab" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading pb-3 mb-3">{{ __('lang.email_contents') }}</div>
                        <div 
                            class="contents-table table-init" 
                            data-url="{{ LaravelLocalization::localizeUrl('/admin/emails/contents') }}" 
                            data-columns="Name,Action" 
                            data-search="true"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="v-pills-emails" role="tabpanel" aria-labelledby="v-pills-emails-tab" tabindex="0">
                <div class="card">
                    <div class="card-body">
                        <div class="card-heading pb-3 mb-3">{{ __('lang.contact_emails') }}</div>
                        <div 
                            class="contact-table" 
                            data-url="{{ LaravelLocalization::localizeUrl('/admin/emails/contact') }}" 
                            data-columns="Date,Name,Email,Action" 
                            data-search="true"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-content offcanvas-end" tabindex="-1" id="contact-email">
    <div class="offcanvas-header">
        <button type="button" class="action-button btn btn-sm" data-bg="white" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div class="offcanvas-body">
        <div class="detail-card p-3">
            <p class="title">{{ __('lang.subject') }}</p>
            <div class="content" id="subject"></div>
        </div>
        <div class="detail-card p-3 mt-3">
            <p class="title">{{ __('lang.message') }}</p>
            <div class="content" id="message"></div>
        </div>
    </div>
</div>
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl('/admin/emails/contact/delete') }}" 
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