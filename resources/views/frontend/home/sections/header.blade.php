<section class="home-header-area position-relative">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-8 col-xxl-6 mx-auto mb-5">
                <div class="header-text-area d-flex flex-column text-center">
                    <h1 class="title mb-3 animate animate__fadeIn">
                        {{ widget('home','top_area','title') }}
                    </h1>
                    <p class="text mb-0 animate animate__fadeIn" data-anm-delay="800ms">
                        {{ widget('home','top_area','text') }}
                    </p>
                </div>
            </div>
            <div class="w-100"></div>
            <div class="col-lg-8 col-xxl-7">
                <div class="uploader-card card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-1 show mb-2" role="alert">
                                @foreach ($errors->all() as $error)
                                    <p class="m-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <div class="file-dropdown-add btn-group mb-2 d-none">
                            <button 
                                type="button" 
                                class="btn btn-color-4 dropdown-toggle"
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                                {{ __('lang.add_more_files') }}
                            </button>
                            <div class="file-dropdown dropdown-menu">
                                <div class="file-dropdown-item">
                                    <div class="dropzone-select dropdown-item">
                                        {{ __('lang.from_device') }}
                                    </div>
                                </div>
                                <div class="file-dropdown-item">
                                    <div 
                                        class="dropdown-item" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#from-link-modal">
                                        {{ __('lang.from_url') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropzone dropzone-uploader d-flex">
                            <div class="dropzone-intro dz-message m-auto">
                                <h2 class="mb-3">{{ __('lang.drop_file_here') }}</h2>
                                <div class="btn-group">
                                    <button 
                                        type="button" 
                                        class="btn btn-color-1 dropdown-toggle" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false">
                                        {{ __('lang.select_files') }}
                                    </button>
                                    <div class="file-dropdown dropdown-menu">
                                        <div class="file-dropdown-item">
                                            <div class="dropdown-item dropzone-select">
                                                {{ __('lang.from_device') }}
                                            </div>
                                        </div>
                                        <div class="file-dropdown-item">
                                            <div 
                                                class="dropdown-item" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#from-link-modal">
                                                {{ __('lang.from_url') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <span class="upload-info-text">{{ __(
                                        'lang.upload_up_to', 
                                        [
                                            'max' => config('upload.MAX_FILE_COUNT'),
                                            'maxsize' => formatKiloBytes(config('upload.MAX_FILE_SIZE'))
                                        ]
                                    ) }}</span>
                                </div>
                            </div>
                            <div class="dropzone-scroll w-100 d-none">
                                <div class="dropzone-previews w-100 d-none">
                                    <div class="dropzone-previews-inner mt-2">
                                        <div class="row align-items-center">
                                            <div class="col-5 col-sm-4 col-md-2">
                                                <div class="file-extension d-flex">
                                                    <span class="m-auto"></span>
                                                </div>
                                                <div class="file-thumb covered d-none"></div>
                                            </div>
                                            <div class="col-7 col-sm-8 col-md-4 dropzone-file">
                                                <div class="dropzone-file-info">
                                                    <div class="file-name" data-dz-name></div>
                                                    <div class="file-size" data-dz-size></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 dropzone-toolbar mt-2 mt-md-0">
                                                <div class="d-flex gap-1 gap-md-2 align-items-center justify-content-sm-end flex-wrap flex-sm-nowrap">
                                                    <a 
                                                        href="#"
                                                        class="dropzone-settings btn btn-link p-0" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#settings-modal">
                                                        <span 
                                                            class="dropzone-tools" 
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            data-bs-custom-class="custom-tooltip" 
                                                            data-bs-title="{{ __('lang.settings') }}">
                                                            <i class="fa-solid fa-gear fa-lg pe-none"></i>
                                                        </span>
                                                    </a>
                                                    <a 
                                                        href="#"
                                                        class="dropzone-delete btn btn-link p-0" 
                                                        aria-label="Remove" 
                                                        data-dz-remove>
                                                        <span 
                                                            class="delete-tooltip dropzone-tools" 
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            data-bs-custom-class="custom-tooltip" 
                                                            data-bs-title="{{ __('lang.remove') }}">
                                                            <i class="fa-regular fa-circle-xmark fa-lg pe-none"></i>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="dropzone-progress-bar mt-3 d-none">
                                                <div class="progress" role="progressbar" aria-label="Progress" 
                                                    aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">
                                                    <div class="progress-bar" style="width: 1%"></div>
                                                </div>
                                            </div>
                                            <div class="dropzone-processing mt-2 d-none">
                                                <span class="spinner-border spinner-border-sm pe-none" role="status"></span>
                                                <span>{{ __('lang.processing') }}</span>
                                            </div>
                                            <div class="dropzone-error mt-1 d-none">
                                                <div>
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    <span data-dz-errormessage></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between">
                            <button 
                                type="button" 
                                class="file-dropdown-copy file-dropdown-button copy-this btn btn-color-4 mt-3 d-none"
                                data-copy="">
                                {{ __('lang.copy_all') }}
                            </button>
                            <button 
                                type="button" 
                                class="file-dropdown-download file-dropdown-button btn btn-color-4 ms-sm-2 mt-3 d-none" 
                                data-action="{{ LaravelLocalization::localizeUrl('/download-single') }}" 
                                data-action-zip="{{ LaravelLocalization::localizeUrl('/download-zip') }}" 
                                data-keys="">
                                <span class="spinner-border spinner-border-sm pe-none d-none" role="status"></span>
                                <span class="pe-none">{{ __('lang.download_all') }}</span>
                            </button>
                            <button 
                                type="button" 
                                class="upload-files btn btn-color-1 ms-sm-auto mt-3 d-none" 
                                id="upload-files">
                                <span class="upload-button-text pe-none">
                                    <span class="d-none d-md-block">{{ __('lang.start_upload') }}</span>
                                    <span class="d-md-none">{{ __('lang.upload') }}</span>
                                </span>
                                <span class="spinner-border spinner-border-sm text-light pe-none d-none" role="status"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="area pe-none d-none d-xl-block">
        <ul class="squares">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</section>