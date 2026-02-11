<div class="width-lg py-5 m-auto">
    <div class="card">
        <div class="card-body">
            <div class="row align-items-center justify-content-center g-5">
                <div class="col-xl-6">
                    <img 
                        src="{{ img('page', widget('404','404_content','image'), 'lg') }}" 
                        alt="404 image" 
                        class="notice-image w-100 mw-100" 
                        width="320" 
                        height="400">
                </div>
                <div class="col-xl-6">
                    @if (widget('404','404_content','title'))
                        <h2 class="notice-title m-0">{{ widget('404','404_content','title') }}</h2>
                    @endif
                    @if (widget('404','404_content','text'))
                        <p class="my-4">{{ widget('404','404_content','text') }}</p>
                    @endif
                    <a href="{{ LaravelLocalization::localizeUrl('/') }}" class="btn btn-color-1">{{ __('lang.go_home') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>