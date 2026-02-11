@extends("admin.layouts.dashboard")
@section("content")
<form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/affiliate/settings') }}">
    <div class="row">
        <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-color-1 w-100">
                        {{ __('lang.save') }}
                    </button>
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
            <div class="card">
                <div class="card-body">
                    <div class="card-heading pb-3 mb-3">{{ __('lang.affiliate_settings') }}</div>
                    <div class="row">
                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4 mb-sm-0 mb-lg-0 mb-md-4">
                            <label for="status" class="form-label">{{ __('lang.status') }}</label>
                            <select class="form-select" name="status" id="status" aria-label="Status">
                                <option value="1"{{ affiliateSetting('status') == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                <option value="0"{{ affiliateSetting('status') == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-12 col-lg-6">
                            <label for="type" class="form-label">{{ __('lang.earning_type') }}</label>
                            <select class="form-select" name="type" id="type" aria-label="Type">
                                <option value="1"{{ affiliateSetting('type') == 1 ? ' selected' : '' }}>{{ __('lang.per_view') }}</option>
                                <option value="2"{{ affiliateSetting('type') == 2 ? ' selected' : '' }}>{{ __('lang.per_download') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>
@stop