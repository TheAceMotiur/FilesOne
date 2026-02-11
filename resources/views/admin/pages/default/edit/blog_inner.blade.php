@extends("admin.layouts.dashboard")
@section("content")
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl("/admin/pages/default/edit/{$page['id']}") }}" 
    enctype="multipart/form-data" 
    class="page-edit-form">
    <div class="row">
        <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <div class="setting-tabs nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a href="?tab=basic" class="nav-link active" id="v-pills-basic-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-basic" role="tab" aria-controls="v-pills-basic" aria-selected="true">{{ __('lang.basic_settings') }}</a>
                        <a href="?tab=header" class="nav-link" id="v-pills-header-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-header" role="tab" aria-controls="v-pills-header" aria-selected="false">{{ __('lang.page_header') }}</a>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-color-1 w-100">
                            {{ __('lang.update') }}
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
                                <div class="col-sm-6 col-md-12 col-lg-9 mb-4">
                                    <label for="page-name" class="form-label">{{ __('lang.page_name') }}</label>
                                    <input type="text" class="form-control" name="page-name" id="page-name" value="{{ $page['name'] }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-3 mb-4">
                                    <label for="status" class="form-label">{{ __('lang.status') }}</label>
                                    <select class="form-select" name="status" id="status" aria-label="Status" disabled>
                                        <option value="1"{{ $page['status'] == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ $page['status'] == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="page-slug" class="form-label">{{ __('lang.page_url') }}</label>
                                    <input type="text" class="form-control" name="page-slug" id="page-slug" value="{{ $page['url'] }}" autocomplete="off">
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
                                <div class="col-sm-6 col-md-12 col-lg-6">
                                    <label for="bg-image" class="form-label">{{ __('lang.bg_image') }}</label>
                                    <input type="file" class="form-control" name="content[header][bg_image]" id="bg-image">
                                    @if (isset($content['header']['bg_image']))
                                        <div class="img-container d-flex p-3 mt-4">
                                            <div class="covered" style="background: url({{ img('page', $content['header']['bg_image']) }});"></div>
                                        </div>
                                    @endif
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