@extends("admin.layouts.dashboard")
@section("content")
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl('/admin/pages/custom/add') }}" 
    enctype="multipart/form-data" 
    class="page-edit-form">
    <div class="row">
        <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="setting-tabs nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a href="?tab=basic" class="nav-link active" id="v-pills-basic-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-basic" role="tab" aria-controls="v-pills-basic" aria-selected="true">{{ __('lang.basic_settings') }}</a>
                        <a href="?tab=seo" class="nav-link" id="v-pills-seo-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-seo" role="tab" aria-controls="v-pills-seo" aria-selected="false">{{ __('lang.seo_settings') }}</a>
                        <a href="?tab=header" class="nav-link" id="v-pills-header-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-header" role="tab" aria-controls="v-pills-header" aria-selected="false">{{ __('lang.page_header') }}</a>
                        <a href="?tab=content" class="nav-link" id="v-pills-content-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-content" role="tab" aria-controls="v-pills-content" aria-selected="false">{{ __('lang.page_content') }}</a>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-color-1 w-100">
                            {{ __('lang.save') }}
                        </button>
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
                <div class="tab-pane fade show active" id="v-pills-basic" role="tabpanel" aria-labelledby="v-pills-basic-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.basic_settings') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-9 mb-4 mb-sm-0 mb-md-4 mb-lg-0">
                                    <label for="page-name" class="form-label">{{ __('lang.page_name') }}</label>
                                    <input type="text" class="form-control" name="page-name" id="page-name" value="{{ old('page-name') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-3">
                                    <label for="status" class="form-label">{{ __('lang.status') }}</label>
                                    <select class="form-select" name="status" id="status" aria-label="Status">
                                        <option value="1"{{ old('status') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ old('status') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-seo" role="tabpanel" aria-labelledby="v-pills-seo-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.seo_settings') }}</div>
                            <div class="row">
                                <div class="mb-4">
                                    <label for="keywords" class="form-label">{{ __('lang.keywords') }}</label>
                                    <input type="text" class="form-control" name="keywords" id="keywords" value="{{ old('keywords') }}" autocomplete="off">
                                </div>
                                <div class="mb-4">
                                    <label for="description" class="form-label">{{ __('lang.description') }}</label>
                                    <textarea class="form-control" name="description" id="description" rows="2" autocomplete="off">{{ old('description') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="og-title" class="form-label">{{ __('lang.og_title') }}</label>
                                    <input type="text" class="form-control" name="og-title" id="og-title" value="{{ old('og-title') }}" autocomplete="off">
                                </div>
                                <div class="mb-4">
                                    <label for="og-description" class="form-label">{{ __('lang.og_description') }}</label>
                                    <textarea class="form-control" name="og-description" id="og-description" rows="2" autocomplete="off">{{ old('og-description') }}</textarea>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4 mb-sm-0 mb-lg-0 mb-md-4">
                                    <label for="og-image" class="form-label">{{ __('lang.og_image') }}</label>
                                    <input type="file" class="form-control" name="og-image" id="og-image">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6">
                                    <label for="no-index" class="form-label">{{ __('lang.search_engine') }}</label>
                                    <select class="form-select" name="no-index" id="no-index" aria-label="Search Engine Crawlers">
                                        <option value="1"{{ old('no-index') == 1 ? ' selected' : '' }}>{{ __('lang.allow') }}</option>
                                        <option value="0"{{ old('no-index') == 0 ? ' selected' : '' }}>{{ __('lang.block') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-header" role="tabpanel" aria-labelledby="v-pills-header-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.page_header') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="upper-title" class="form-label">{{ __('lang.upper_title') }}</label>
                                    <input type="text" name="content[header][upper_title]" class="form-control" id="upper-title" value="{{ old('content.header.upper_title') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="header-title" class="form-label">{{ __('lang.title') }}</label>
                                    <input type="text" name="content[header][title]" class="form-control" id="header-title" value="{{ old('content.header.title') }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6">
                                    <label for="bg-image" class="form-label">{{ __('lang.bg_image') }}</label>
                                    <input type="file" class="form-control" name="content[header][bg_image]" id="bg-image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-content" role="tabpanel" aria-labelledby="v-pills-content-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.page_content') }}</div>
                            <div class="row">
                                <div>
                                    <textarea 
                                        name="content[content]" 
                                        data-upload="{{ LaravelLocalization::localizeUrl("/admin/pages/custom/upload-file") }}" 
                                        data-delete="{{ LaravelLocalization::localizeUrl("/admin/pages/custom/delete-file") }}" 
                                        class="editor editor-init">{{ old('content.content') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>
@stop