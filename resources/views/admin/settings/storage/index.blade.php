@extends("admin.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
        <div class="card">
            <div class="card-body">
                <div class="setting-tabs nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a href="?tab=settings" class="nav-link active" id="v-pills-settings-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="true">{{ __('lang.settings') }}</a>
                    <a href="?tab=storages" class="nav-link" id="v-pills-storages-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-storages" role="tab" aria-controls="v-pills-storages" aria-selected="false">{{ __('lang.storages') }}</a>
                    <a href="?tab=temp-files" class="nav-link" id="v-pills-temp-files-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-temp-files" role="tab" aria-controls="v-pills-temp-files" aria-selected="false">{{ __('lang.clear_temp_files') }}</a>
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
            <div class="tab-pane fade show active" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.storage_settings') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="default-storage" class="form-label">{{ __('lang.default_storage') }}</label>
                                    <select class="form-select" name="default-storage" id="default-storage" aria-label="Default Storage">
                                        <option value="1"{{ defaultStorage()->id == 1 ? ' selected' : '' }}>
                                            {{ storageSetting(1)->name }}
                                        </option>
                                        <option value="2"{{ defaultStorage()->id == 2 ? ' selected' : '' }}>
                                            {{ storageSetting(2)->name }}
                                        </option>
                                        <option value="3"{{ defaultStorage()->id == 3 ? ' selected' : '' }}>
                                            {{ storageSetting(3)->name }}
                                        </option>
                                        <option value="4"{{ defaultStorage()->id == 4 ? ' selected' : '' }}>
                                            {{ storageSetting(4)->name }}
                                        </option>
                                        <option value="5"{{ defaultStorage()->id == 5 ? ' selected' : '' }}>
                                            {{ storageSetting(5)->name }}
                                        </option>
                                        @foreach($googleDrives as $googleDrive)
                                        <option value="{{ $googleDrive->id }}"{{ defaultStorage()->id == $googleDrive->id ? ' selected' : '' }}>
                                            {{ $googleDrive->name }}
                                        </option>
                                        @endforeach
                                    </select>
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
            <div class="tab-pane fade" id="v-pills-storages" role="tabpanel" aria-labelledby="v-pills-storages-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/s3') }}">
                    @php
                        $amazonS3Features = json_decode($amazonS3->value, true);
                    @endphp
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">Amazon S3</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="amazon-key" class="form-label">{{ __('lang.amazon_key') }}</label>
                                    <input type="text" class="form-control" name="amazon-key" id="amazon-key" value="{{ $amazonS3Features['access_key_id'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="amazon-secret" class="form-label">{{ __('lang.amazon_secret') }}</label>
                                    <input type="text" class="form-control" name="amazon-secret" id="amazon-secret" value="{{ $amazonS3Features['secret_access_key'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="amazon-region" class="form-label">{{ __('lang.amazon_region') }}</label>
                                    <input type="text" class="form-control" name="amazon-region" id="amazon-region" value="{{ $amazonS3Features['default_region'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="amazon-bucket" class="form-label">{{ __('lang.amazon_bucket') }}</label>
                                    <input type="text" class="form-control" name="amazon-bucket" id="amazon-bucket" value="{{ $amazonS3Features['bucket'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="amazon-url" class="form-label">{{ __('lang.amazon_url') }}</label>
                                    <input type="text" class="form-control" name="amazon-url" id="amazon-url" value="{{ $amazonS3Features['url'] }}" autocomplete="off">
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
                
                <!-- Google Drive Accounts -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
                            <div class="card-heading m-0">{{ __('lang.google_drive_accounts') }}</div>
                            <a href="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/google/add') }}" class="btn btn-sm btn-color-1">
                                <i class="fa-solid fa-plus me-1"></i>
                                {{ __('lang.add_google_drive') }}
                            </a>
                        </div>
                        
                        @if($googleDrives->isEmpty())
                            <p class="no-data text-center py-4">{{ __('lang.no_google_drive_accounts') }}</p>
                        @else
                            @foreach($googleDrives as $index => $googleDrive)
                                @php
                                    $googleFeatures = json_decode($googleDrive->value, true);
                                @endphp
                                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/google/' . $googleDrive->id) }}" class="{{ $index > 0 ? 'mt-4 pt-4 border-top' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0">
                                            {{ $googleDrive->name }}
                                            @if($googleDrive->storage_key)
                                                <span class="badge bg-secondary">{{ $googleDrive->storage_key }}</span>
                                            @endif
                                            @if($googleDrive->default == 1)
                                                <span class="badge bg-success">{{ __('lang.default') }}</span>
                                            @endif
                                        </h6>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="event.preventDefault(); document.getElementById('test-google-{{ $googleDrive->id }}').submit();">
                                                <i class="fa-solid fa-check-circle me-1"></i>
                                                {{ __('lang.test_connection') }}
                                            </button>
                                            @if($googleDrive->default != 1)
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="if(confirm('{{ __('lang.confirm_delete') }}')) { event.preventDefault(); document.getElementById('delete-google-{{ $googleDrive->id }}').submit(); }">
                                                <i class="fa-solid fa-trash me-1"></i>
                                                {{ __('lang.delete') }}
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Bandwidth Usage Display --}}
                                    @php
                                        $bandwidthUsed = $googleDrive->bandwidth_used ?? 0;
                                        $bandwidthLimit = $googleDrive->bandwidth_limit ?? 751619276800;
                                        $bandwidthUsedGB = round($bandwidthUsed / (1024 * 1024 * 1024), 2);
                                        $bandwidthLimitGB = round($bandwidthLimit / (1024 * 1024 * 1024), 2);
                                        $bandwidthPercentage = $bandwidthLimit > 0 ? round(($bandwidthUsed / $bandwidthLimit) * 100, 1) : 0;
                                        $bandwidthClass = $bandwidthPercentage >= 85 ? 'danger' : ($bandwidthPercentage >= 70 ? 'warning' : 'success');
                                        $resetAt = $googleDrive->bandwidth_reset_at ? \Carbon\Carbon::parse($googleDrive->bandwidth_reset_at)->diffForHumans() : 'Not set';
                                    @endphp
                                    
                                    @if($bandwidthUsed > 0 || $googleDrive->bandwidth_reset_at)
                                    <div class="alert alert-{{ $bandwidthClass }} alert-dismissible fade show mb-3" role="alert">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fa-solid fa-download me-2"></i>
                                                <strong>{{ __('lang.bandwidth_usage') }}:</strong> 
                                                {{ $bandwidthUsedGB }} GB / {{ $bandwidthLimitGB }} GB ({{ $bandwidthPercentage }}%)
                                            </div>
                                            <div class="text-end">
                                                <small>{{ __('lang.resets') }}: {{ $resetAt }}</small>
                                            </div>
                                        </div>
                                        <div class="progress mt-2" style="height: 8px;">
                                            <div class="progress-bar bg-{{ $bandwidthClass }}" role="progressbar" 
                                                style="width: {{ min($bandwidthPercentage, 100) }}%" 
                                                aria-valuenow="{{ $bandwidthPercentage }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="row">
                                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                            <label for="google-client-id-{{ $googleDrive->id }}" class="form-label">{{ __('lang.google_drive_client_id') }}</label>
                                            <input type="text" class="form-control" name="google-client-id" 
                                                id="google-client-id-{{ $googleDrive->id }}" 
                                                value="{{ $googleFeatures['client_id'] ?? '' }}" autocomplete="off">
                                        </div>
                                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                            <label for="google-client-secret-{{ $googleDrive->id }}" class="form-label">{{ __('lang.google_drive_client_secret') }}</label>
                                            <input type="text" class="form-control" name="google-client-secret" 
                                                id="google-client-secret-{{ $googleDrive->id }}" 
                                                value="{{ $googleFeatures['client_secret'] ?? '' }}" autocomplete="off">
                                        </div>
                                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                            <label for="google-refresh-token-{{ $googleDrive->id }}" class="form-label">{{ __('lang.google_drive_refresh_token') }}</label>
                                            <input type="text" class="form-control" name="google-refresh-token" 
                                                id="google-refresh-token-{{ $googleDrive->id }}" 
                                                value="{{ $googleFeatures['refresh_token'] ?? '' }}" autocomplete="off">
                                        </div>
                                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                            <label for="google-folder-{{ $googleDrive->id }}" class="form-label">{{ __('lang.google_drive_folder') }}</label>
                                            <input type="text" class="form-control" name="google-folder" 
                                                id="google-folder-{{ $googleDrive->id }}" 
                                                value="{{ $googleFeatures['folder'] ?? '' }}" autocomplete="off">
                                        </div>
                                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                            <label for="bandwidth-limit-{{ $googleDrive->id }}" class="form-label">
                                                {{ __('lang.bandwidth_limit_gb') }}
                                                <i class="fa-solid fa-info-circle ms-1" data-bs-toggle="tooltip" 
                                                    title="{{ __('lang.bandwidth_limit_help') }}"></i>
                                            </label>
                                            <input type="number" class="form-control" name="bandwidth-limit" 
                                                id="bandwidth-limit-{{ $googleDrive->id }}" 
                                                value="{{ round(($googleDrive->bandwidth_limit ?? 751619276800) / (1024 * 1024 * 1024)) }}" 
                                                min="1" max="10000" step="1" autocomplete="off">
                                            <small class="form-text text-muted">{{ __('lang.default_700gb_free_accounts') }}</small>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-color-1">
                                                <i class="spinner fa-solid fa-check me-1"></i>
                                                {{ __('lang.save') }}
                                            </button>
                                        </div>
                                    </div>
                                    @csrf
                                </form>
                                
                                <!-- Hidden forms for test and delete-->
                                <form id="test-google-{{ $googleDrive->id }}" method="POST" 
                                    action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/google/test/' . $googleDrive->id) }}" class="d-none">
                                    @csrf
                                </form>
                                <form id="delete-google-{{ $googleDrive->id }}" method="POST" 
                                    action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/google/delete/' . $googleDrive->id) }}" class="d-none">
                                    @csrf
                                </form>
                            @endforeach
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/r2') }}">
                    @php
                        $cloudflareR2Features = json_decode($cloudflareR2->value, true);
                    @endphp
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">Cloudflare R2</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="cloudflare-key" class="form-label">{{ __('lang.cloudflare_key') }}</label>
                                    <input type="text" class="form-control" name="cloudflare-key" id="cloudflare-key" value="{{ $cloudflareR2Features['access_key_id'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="cloudflare-secret" class="form-label">{{ __('lang.cloudflare_secret') }}</label>
                                    <input type="text" class="form-control" name="cloudflare-secret" id="cloudflare-secret" value="{{ $cloudflareR2Features['secret_access_key'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="cloudflare-bucket" class="form-label">{{ __('lang.cloudflare_bucket') }}</label>
                                    <input type="text" class="form-control" name="cloudflare-bucket" id="cloudflare-bucket" value="{{ $cloudflareR2Features['bucket'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="cloudflare-url" class="form-label">{{ __('lang.cloudflare_url') }}</label>
                                    <input type="text" class="form-control" name="cloudflare-url" id="cloudflare-url" value="{{ $cloudflareR2Features['url'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="cloudflare-endpoint" class="form-label">{{ __('lang.cloudflare_endpoint') }}</label>
                                    <input type="text" class="form-control" name="cloudflare-endpoint" id="cloudflare-endpoint" value="{{ $cloudflareR2Features['endpoint'] }}" autocomplete="off">
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
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/wasabi') }}">
                    @php
                        $wasabiFeatures = json_decode($wasabi->value, true);
                    @endphp
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">Wasabi</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="wasabi-key" class="form-label">{{ __('lang.wasabi_key') }}</label>
                                    <input type="text" class="form-control" name="wasabi-key" id="wasabi-key" value="{{ $wasabiFeatures['access_key_id'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="wasabi-secret" class="form-label">{{ __('lang.wasabi_secret') }}</label>
                                    <input type="text" class="form-control" name="wasabi-secret" id="wasabi-secret" value="{{ $wasabiFeatures['secret_access_key'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="wasabi-region" class="form-label">{{ __('lang.wasabi_region') }}</label>
                                    <input type="text" class="form-control" name="wasabi-region" id="wasabi-region" value="{{ $wasabiFeatures['default_region'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="wasabi-bucket" class="form-label">{{ __('lang.wasabi_bucket') }}</label>
                                    <input type="text" class="form-control" name="wasabi-bucket" id="wasabi-bucket" value="{{ $wasabiFeatures['bucket'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="wasabi-url" class="form-label">{{ __('lang.wasabi_url') }}</label>
                                    <input type="text" class="form-control" name="wasabi-url" id="wasabi-url" value="{{ $wasabiFeatures['url'] }}" autocomplete="off">
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
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/ftp') }}">
                    @php
                        $ftpFeatures = json_decode($ftp->value, true);
                    @endphp
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">FTP</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="ftp-host" class="form-label">{{ __('lang.ftp_host') }}</label>
                                    <input type="text" class="form-control" name="ftp-host" id="ftp-host" value="{{ $ftpFeatures['host'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="ftp-username" class="form-label">{{ __('lang.ftp_username') }}</label>
                                    <input type="text" class="form-control" name="ftp-username" id="ftp-username" value="{{ $ftpFeatures['username'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="ftp-password" class="form-label">{{ __('lang.ftp_password') }}</label>
                                    <input type="text" class="form-control" name="ftp-password" id="ftp-password" value="{{ $ftpFeatures['password'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="ftp-port" class="form-label">{{ __('lang.ftp_port') }}</label>
                                    <input type="text" class="form-control" name="ftp-port" id="ftp-port" value="{{ $ftpFeatures['port'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="ftp-path" class="form-label">{{ __('lang.ftp_path') }}</label>
                                    <input type="text" class="form-control" name="ftp-path" id="ftp-path" value="{{ $ftpFeatures['path'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="ftp-url" class="form-label">{{ __('lang.ftp_url') }}</label>
                                    <input type="text" class="form-control" name="ftp-url" id="ftp-url" value="{{ $ftpFeatures['url'] }}" autocomplete="off">
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
            <div class="tab-pane fade" id="v-pills-temp-files" role="tabpanel" aria-labelledby="v-pills-temp-files-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/settings/storage/temp-files') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.clear_temp_files') }}</div>
                            <p class="no-data">{{ __('lang.clear_temp_files_text') }}</p>
                            <div class="text-center">
                                <button type="submit" class="btn btn-color-1">
                                    <i class="spinner fa-solid fa-check me-1"></i>
                                    {{ __('lang.clear') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@stop