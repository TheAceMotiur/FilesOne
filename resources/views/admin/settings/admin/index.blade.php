@extends("admin.layouts.dashboard")
@section("content")
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl("/admin/settings/admin") }}" 
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
                    <div class="card-heading pb-3 mb-3">{{ __('lang.admin_settings') }}</div>
                    <div class="row">
                        <div class="col-sm-6 mb-4">
                            <label for="name" class="form-label">{{ __('lang.name') }}</label>
                            <input type="text" name="name" class="form-control" id="name" value="{{ userData('name') }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="email" class="form-label">{{ __('lang.email') }}</label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ userData('email') }}" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="password" class="form-label">{{ __('lang.current_password') }}</label>
                            <input type="password" name="password" class="form-control" id="password" autocomplete="off">
                        </div>
                        <div class="w-100"></div>
                        <div class="col-sm-6 mb-4">
                            <label for="password-new" class="form-label">{{ __('lang.new_password') }}</label>
                            <input type="password" name="password-new" class="form-control" id="password-new" autocomplete="off">
                        </div>
                        <div class="col-sm-6 mb-4">
                            <label for="password-new-confirmation" class="form-label">{{ __('lang.new_password_again') }}</label>
                            <input type="password" name="password-new_confirmation" class="form-control" id="password-new-confirmation" autocomplete="off">
                        </div>
                        <div class="col-sm-6">
                            <label for="photo" class="form-label">{{ __('lang.photo') }}</label>
                            <input type="file" class="form-control" name="photo" id="photo">
                            @if (userData('photo'))
                                <div class="img-container d-flex p-3 mt-4">
                                    <div class="covered" style="background: url({{ img('user', userData('photo')) }});"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>
@stop