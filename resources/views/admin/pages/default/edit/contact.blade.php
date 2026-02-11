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
                        <a href="?tab=seo" class="nav-link" id="v-pills-seo-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-seo" role="tab" aria-controls="v-pills-seo" aria-selected="false">{{ __('lang.seo_settings') }}</a>
                        <a href="?tab=header" class="nav-link" id="v-pills-header-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-header" role="tab" aria-controls="v-pills-header" aria-selected="false">{{ __('lang.page_header') }}</a>
                        <a href="?tab=content" class="nav-link" id="v-pills-content-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-content" role="tab" aria-controls="v-pills-content" aria-selected="false">{{ __('lang.page_content') }}</a>
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
                <div class="tab-pane fade" id="v-pills-seo" role="tabpanel" aria-labelledby="v-pills-seo-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.seo_settings') }}</div>
                            <div class="row">
                                <div class="mb-4">
                                    <label for="keywords" class="form-label">{{ __('lang.keywords') }}</label>
                                    <input type="text" class="form-control" name="keywords" id="keywords" value="{{ $seo['keywords'] }}" autocomplete="off">
                                </div>
                                <div class="mb-4">
                                    <label for="description" class="form-label">{{ __('lang.description') }}</label>
                                    <textarea class="form-control" name="description" id="description" rows="2" autocomplete="off">{{ $seo['description'] }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="og-title" class="form-label">{{ __('lang.og_title') }}</label>
                                    <input type="text" class="form-control" name="og-title" id="og-title" value="{{ $seo['og_title'] }}" autocomplete="off">
                                </div>
                                <div class="mb-4">
                                    <label for="og-description" class="form-label">{{ __('lang.og_description') }}</label>
                                    <textarea class="form-control" name="og-description" id="og-description" rows="2" autocomplete="off">{{ $seo['og_description'] }}</textarea>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4 mb-sm-0 mb-lg-0 mb-md-4">
                                    <label for="og-image" class="form-label">{{ __('lang.og_image') }}</label>
                                    <input type="file" class="form-control" name="og-image" id="og-image">
                                    @if ($seo['og_image'])
                                        <div class="img-container d-flex p-3 mt-4">
                                            <div class="covered" style="background: url({{ img('page', $seo['og_image']) }});"></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6">
                                    <label for="no-index" class="form-label">{{ __('lang.search_engine') }}</label>
                                    <select class="form-select" name="no-index" id="no-index" aria-label="Search Engine Crawlers">
                                        <option value="1"{{ $seo['no_index'] == 1 ? ' selected' : '' }}>{{ __('lang.allow') }}</option>
                                        <option value="0"{{ $seo['no_index'] == 0 ? ' selected' : '' }}>{{ __('lang.block') }}</option>
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
                                    <label for="header-upper-title" class="form-label">{{ __('lang.upper_title') }}</label>
                                    <input type="text" name="content[header][upper_title]" class="form-control" id="header-upper-title" value="{{ $content['header']['upper_title'] ?? '' }}" autocomplete="off">
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="header-title" class="form-label">{{ __('lang.title') }}</label>
                                    <input type="text" name="content[header][title]" class="form-control" id="header-title" value="{{ $content['header']['title'] ?? '' }}" autocomplete="off">
                                </div>
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
                <div class="tab-pane fade" id="v-pills-content" role="tabpanel" aria-labelledby="v-pills-content-tab" tabindex="0">
                    <div class="card">
                        <div class="card-body">
                            <a class="card-heading collapsed pb-3 mb-3" data-bs-toggle="collapse" href="#collapseBoxes" role="button" aria-expanded="false" aria-controls="collapseBoxes">
                                {{ __('lang.boxes') }}
                            </a>
                            <div class="collapse" id="collapseBoxes">
                                <div class="row mt-4">
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-1-icon" class="form-label">{{ __('lang.box_icon') }} #1</label>
                                        <input type="text" name="content[boxes][box_1_icon]" class="form-control" id="box-1-icon" value="{{ $content['boxes']['box_1_icon'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-1-title" class="form-label">{{ __('lang.box_title') }} #1</label>
                                        <input type="text" name="content[boxes][box_1_title]" class="form-control" id="box-1-title" value="{{ $content['boxes']['box_1_title'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-1-subtitle" class="form-label">{{ __('lang.box_subtitle') }} #1</label>
                                        <input type="text" name="content[boxes][box_1_subtitle]" class="form-control" id="box-1-subtitle" value="{{ $content['boxes']['box_1_subtitle'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-1-text" class="form-label">{{ __('lang.box_text') }} #1</label>
                                        <input type="text" name="content[boxes][box_1_text]" class="form-control" id="box-1-text" value="{{ $content['boxes']['box_1_text'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-2-icon" class="form-label">{{ __('lang.box_icon') }} #2</label>
                                        <input type="text" name="content[boxes][box_2_icon]" class="form-control" id="box-2-icon" value="{{ $content['boxes']['box_2_icon'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-2-title" class="form-label">{{ __('lang.box_title') }} #2</label>
                                        <input type="text" name="content[boxes][box_2_title]" class="form-control" id="box-2-title" value="{{ $content['boxes']['box_2_title'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-2-subtitle" class="form-label">{{ __('lang.box_subtitle') }} #2</label>
                                        <input type="text" name="content[boxes][box_2_subtitle]" class="form-control" id="box-2-subtitle" value="{{ $content['boxes']['box_2_subtitle'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-2-text" class="form-label">{{ __('lang.box_text') }} #2</label>
                                        <input type="text" name="content[boxes][box_2_text]" class="form-control" id="box-2-text" value="{{ $content['boxes']['box_2_text'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-3-icon" class="form-label">{{ __('lang.box_icon') }} #3</label>
                                        <input type="text" name="content[boxes][box_3_icon]" class="form-control" id="box-3-icon" value="{{ $content['boxes']['box_3_icon'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="box-3-title" class="form-label">{{ __('lang.box_title') }} #3</label>
                                        <input type="text" name="content[boxes][box_3_title]" class="form-control" id="box-3-title" value="{{ $content['boxes']['box_3_title'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4 mb-sm-0 mb-lg-0 mb-md-4">
                                        <label for="box-3-subtitle" class="form-label">{{ __('lang.box_subtitle') }} #3</label>
                                        <input type="text" name="content[boxes][box_3_subtitle]" class="form-control" id="box-3-subtitle" value="{{ $content['boxes']['box_3_subtitle'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6">
                                        <label for="box-3-text" class="form-label">{{ __('lang.box_text') }} #3</label>
                                        <input type="text" name="content[boxes][box_3_text]" class="form-control" id="box-3-text" value="{{ $content['boxes']['box_3_text'] ?? '' }}" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body">
                            <a class="card-heading collapsed pb-3 mb-3" data-bs-toggle="collapse" href="#collapseForm" role="button" aria-expanded="false" aria-controls="collapseForm">
                                {{ __('lang.form') }}
                            </a>
                            <div class="collapse" id="collapseForm">
                                <div class="row mt-4">
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="form-upper-title" class="form-label">{{ __('lang.upper_title') }}</label>
                                        <input type="text" name="content[form][upper_title]" class="form-control" id="form-upper-title" value="{{ $content['form']['upper_title'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                        <label for="form-title" class="form-label">{{ __('lang.title') }}</label>
                                        <input type="text" name="content[form][title]" class="form-control" id="form-title" value="{{ $content['form']['title'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="mb-4">
                                        <label for="form-text" class="form-label">{{ __('lang.text') }}</label>
                                        <input type="text" name="content[form][text]" class="form-control" id="form-text" value="{{ $content['form']['text'] ?? '' }}" autocomplete="off">
                                    </div>
                                    <div class="col-sm-6 col-md-12 col-lg-6">
                                        <label for="form-image" class="form-label">{{ __('lang.image') }}</label>
                                        <input type="file" class="form-control" name="content[form][image]" id="form-image">
                                        @if (isset($content['form']['image']))
                                            <div class="img-container d-flex p-3 mt-4">
                                                <div class="covered" style="background: url({{ img('page', $content['form']['image']) }});"></div>
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
    </div>
    @csrf
</form>
@stop