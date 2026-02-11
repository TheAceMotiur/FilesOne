<input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
<div class="modal fade" id="logout" tabindex="-1" aria-hidden="true" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-body text-center">
            <i class="fa-solid fa-triangle-exclamation fa-4x mb-3"></i>
            <h4>{{ __('lang.modal_question') }}</h4>
            <p class="modal-text m-0">{{ __('lang.modal_logout') }}</p>
            @csrf
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-color-4" data-bs-dismiss="modal">{{ __('lang.cancel') }}</button>
            <a href="{{ LaravelLocalization::localizeUrl("/logout") }}" class="btn btn-color-1">{{ __('lang.logout') }}</a>
        </div>
    </div>
    </div>
</div>