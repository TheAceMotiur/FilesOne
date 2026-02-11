@extends("admin.layouts.dashboard")
@section("content")
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl('/admin/affiliate/payout-rates/add') }}" 
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
                    <div class="card-heading pb-3 mb-3">{{ __('lang.add_country') }}</div>
                    <div class="row">
                        <div class="col-sm-6 col-md-12 col-lg-6 mb-4 mb-lg-0 mb-md-4 mb-sm-0">
                            <label for="country" class="form-label">{{ __('lang.country') }}</label>
                            <select class="form-select" name="country" id="country" aria-label="Country">
                                <option selected>{{ __('lang.select_country') }}</option>
                                @foreach ($countries as $key => $name)
                                    <option value="{{ "{$key},{$name}" }}">{{ $name }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-12 col-lg-6">
                            <label for="rate" class="form-label">{{ __('lang.rate_per_1000_downloads') }}</label>
                            <input type="number" name="rate" class="form-control" id="rate" 
                                value="{{ old('rate') }}" min="0" max="1000" step="0.0001" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>
@stop