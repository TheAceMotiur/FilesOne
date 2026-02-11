<form 
    action="{{ LaravelLocalization::localizeUrl(pageSlug('file', true) . "/{$file->short_key}") . '/report' }}" 
    id="file-report-form" 
    method="post">
    <div 
        class="modal fade" 
        id="report-file" 
        tabindex="-1" 
        aria-hidden="true" 
        aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            {{ __('lang.email') }}
                        </label>
                        @if (Auth::check())
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                id="email" 
                                value="{{ Auth::user()->email }}" 
                                autocomplete="off" 
                                readonly>
                        @else
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control" 
                                id="email" 
                                value="{{ old('email') }}" 
                                autocomplete="off">
                        @endif
                    </div>
                    <div>
                        <label for="reason" class="form-label">
                            {{ __('lang.reason') }}
                        </label>
                        <textarea 
                            name="reason" 
                            class="form-control" 
                            id="reason" 
                            rows="6" 
                            autocomplete="off">{{ old('reason') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button 
                        type="button" 
                        class="btn btn-color-4" 
                        data-bs-dismiss="modal">
                        {{ __('lang.cancel') }}
                    </button>
                    <button 
                        type="submit" 
                        class="btn btn-color-1">
                        {{ __('lang.send') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @csrf
</form>