<div class="width-sm card">
    <div class="card-body text-center">
        <h2 class="m-0">{{ $title }}</h2>
        <p class="my-4">{{ $text }}</p>
        <a href="{{ LaravelLocalization::localizeUrl('/') }}" class="btn btn-color-1">{{ __('lang.go_home') }}</a>
    </div>
</div>