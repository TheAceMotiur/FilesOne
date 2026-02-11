<section class="file-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8 col-md-6 col-xl-4">
                <div class="alert alert-3" role="alert">
                    {{ __('lang.file_password_info') }}
                </div>
                <form 
                    method="POST" 
                    action="{{ LaravelLocalization::localizeUrl(pageSlug('file', true) . "/{$file->short_key}") }}">
                    @if ($errors->any())
                        <div class="alert alert-1 show mb-4" role="alert">
                            @foreach ($errors->all() as $error)
                                <p class="m-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-2 show mb-4" role="alert">
                            <p class="m-0">{{ session('success') }}</p>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-1 show mb-4" role="alert">
                            <p class="m-0">{{ session('error') }}</p>
                        </div>
                    @endif
                    <div class="mb-4">
                        <label for="file-password" class="form-label">{{ __('lang.password') }}</label>
                        <input type="password" class="form-control" name="file-password" id="file-password">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-color-1 w-100">{{ __('lang.confirm') }}</button>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
</section>