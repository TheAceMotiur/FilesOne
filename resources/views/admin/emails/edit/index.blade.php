@extends("admin.layouts.dashboard")
@section("content")
<form 
    method="POST" 
    action="{{ LaravelLocalization::localizeUrl("/admin/emails/contents/edit/{$email['id']}") }}">
    <div class="row">
        <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-color-1 w-100">
                        {{ __('lang.update') }}
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
                    <div class="card-heading pb-3 mb-3">{{ __("lang.{$content_name}") }}</div>
                    <div class="row">
                        <div class="mb-4">
                            <label class="form-label">{{ __('lang.tags') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($tags as $tag)
                                    <span class="badge-4 user-select-all">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label for="content" class="form-label">{{ __('lang.content') }}</label>
                            <textarea class="form-control" name="content" id="content" rows="4" autocomplete="off">{{ $email['content'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>
@stop