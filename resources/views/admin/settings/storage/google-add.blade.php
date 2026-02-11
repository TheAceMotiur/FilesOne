@extends("admin.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-12">
        <div class="mb-4">
            <a href="{{ LaravelLocalization::localizeUrl('/admin/settings/storage?tab=storages') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>
                {{ __('lang.back_to_storage') }}
            </a>
        </div>
        
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
        
        <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/google/add') }}">
            <div class="card">
                <div class="card-body">
                    <div class="card-heading pb-3 mb-3">{{ __('lang.add_google_drive') }}</div>
                    
                    <div class="alert alert-info mb-4">
                        <h6><i class="fa-solid fa-info-circle me-1"></i> {{ __('lang.how_to_setup_google_drive') }}</h6>
                        <ol class="mb-0 ps-3">
                            <li>{{ __('lang.google_drive_step_1') }}</li>
                            <li>{{ __('lang.google_drive_step_2') }}</li>
                            <li>{{ __('lang.google_drive_step_3') }}</li>
                            <li>{{ __('lang.google_drive_step_4') }}</li>
                        </ol>
                        <a href="https://console.cloud.google.com/" target="_blank" class="btn btn-sm btn-light mt-2">
                            <i class="fa-solid fa-external-link-alt me-1"></i>
                            {{ __('lang.open_google_console') }}
                        </a>
                        <a href="https://developers.google.com/oauthplayground/" target="_blank" class="btn btn-sm btn-light mt-2">
                            <i class="fa-solid fa-external-link-alt me-1"></i>
                            {{ __('lang.open_oauth_playground') }}
                        </a>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                            <label for="name" class="form-label">
                                {{ __('lang.account_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="name" id="name" 
                                value="{{ old('name') }}" placeholder="{{ __('lang.google_drive_account_name_placeholder') }}" 
                                autocomplete="off" required>
                            <small class="form-text text-muted">{{ __('lang.google_drive_account_name_help') }}</small>
                        </div>
                        
                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                            <label for="google-client-id" class="form-label">
                                {{ __('lang.google_drive_client_id') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="google-client-id" id="google-client-id" 
                                value="{{ old('google-client-id') }}" autocomplete="off" required>
                        </div>
                        
                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                            <label for="google-client-secret" class="form-label">
                                {{ __('lang.google_drive_client_secret') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="google-client-secret" id="google-client-secret" 
                                value="{{ old('google-client-secret') }}" autocomplete="off" required>
                        </div>
                        
                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                            <label for="google-refresh-token" class="form-label">
                                {{ __('lang.google_drive_refresh_token') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" name="google-refresh-token" id="google-refresh-token" 
                                value="{{ old('google-refresh-token') }}" autocomplete="off" required>
                        </div>
                        
                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                            <label for="google-folder" class="form-label">
                                {{ __('lang.google_drive_folder') }} <small class="text-muted">({{ __('lang.optional') }})</small>
                            </label>
                            <input type="text" class="form-control" name="google-folder" id="google-folder" 
                                value="{{ old('google-folder') }}" autocomplete="off">
                            <small class="form-text text-muted">{{ __('lang.google_drive_folder_help') }}</small>
                        </div>
                        
                        <div class="col-12">
                            <div class="text-center">
                                <button type="submit" class="btn btn-color-1">
                                    <i class="spinner fa-solid fa-plus me-1"></i>
                                    {{ __('lang.add_google_drive') }}
                                </button>
                                <a href="{{ LaravelLocalization::localizeUrl('/admin/settings/storage?tab=storages') }}" class="btn btn-secondary">
                                    {{ __('lang.cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @csrf
        </form>
    </div>
</div>
@stop
