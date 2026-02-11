@extends("admin.layouts.dashboard")
@section("content")
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl('/admin/affiliate/withdrawal-methods/add') }}" 
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
                    <div class="card-heading pb-3 mb-3">{{ __('lang.add_method') }}</div>
                    <div class="row">
                        <div class="col-sm-6 col-md-12 col-lg-4 mb-4">
                            <label for="name" class="form-label">{{ __('lang.name') }}</label>
                            <input type="text" name="name" class="form-control" id="name" 
                                value="{{ old('name') }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 col-md-12 col-lg-4 mb-4">
                            <label for="minimum" class="form-label">{{ __('lang.min_withdrawal_amount') }}</label>
                            <input type="number" name="minimum" class="form-control" id="minimum" 
                                value="{{ old('withdrawal-amount') }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 col-md-12 col-lg-4 mb-4">
                            <label for="status" class="form-label">{{ __('lang.withdrawal_status') }}</label>
                            <select class="form-select" name="status" id="status" aria-label="Status">
                                <option value="1">{{ __('lang.enable') }}</option>
                                <option value="0">{{ __('lang.disable') }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="description" class="form-label">{{ __('lang.description') }}</label>
                            <textarea class="form-control" name="description" id="description" rows="3" 
                                autocomplete="off"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>
@stop