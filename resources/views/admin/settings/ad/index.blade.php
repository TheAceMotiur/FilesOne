@extends("admin.layouts.dashboard")
@section("content")
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl('/admin/settings/ad') }}" 
    enctype="multipart/form-data">
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
                    <div class="card-heading pb-3 mb-3">{{ __('lang.ad_settings') }}</div>
                    <div class="row">
                        <div class="mb-4">
                            <label for="top-area" class="form-label">{{ __('lang.top_area') }}</label>
                            <textarea class="form-control" name="top-area" id="top-area" rows="3" 
                                autocomplete="off">{{ ads('top_area') }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="middle-area" class="form-label">{{ __('lang.middle_area') }}</label>
                            <textarea class="form-control" name="middle-area" id="middle-area" rows="3" 
                                autocomplete="off">{{ ads('middle_area') }}</textarea>
                        </div>
                        <div>
                            <label for="bottom-area" class="form-label">{{ __('lang.bottom_area') }}</label>
                            <textarea class="form-control" name="bottom-area" id="bottom-area" rows="3" 
                                autocomplete="off">{{ ads('bottom_area') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>
@stop