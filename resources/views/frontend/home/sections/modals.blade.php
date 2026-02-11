<form 
    action="{{ LaravelLocalization::localizeUrl('/get-file') }}" 
    id="add-file-url-form" 
    method="POST">
    <div 
        class="modal fade" 
        id="from-link-modal" 
        tabindex="-1" 
        aria-hidden="true"
        aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-body">
                    <label class="form-label" for="file-url">
                        {{ __('lang.add_file_url') }}
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="file-url" 
                        name="url" 
                        placeholder="{{ __('lang.write_url') }}">
                    <div class="url-input-error dropzone-error mt-2 d-none"></div>
                </div>
                <div class="modal-footer">
                    <button 
                        type="submit" 
                        class="btn btn-color-1">
                        <span 
                            class="spinner-border spinner-border-sm text-light d-none" 
                            role="status">
                        </span>
                        {{ __('lang.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<form 
    action="#" 
    id="settings-form" 
    class="file-settings-form" 
    method="POST">
    <div 
        class="modal fade" 
        id="settings-modal" 
        tabindex="-1" 
        aria-hidden="true"
        aria-modal="true" 
        aria-labelledby="fileModalLabel">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title m-0" id="fileModalLabel">
                        {{ __('lang.settings') }}
                    </h5>
                </div>
                <div class="modal-body scroll-light py-0">
                    <div class="settings-container row mb-3 p-2">
                        <div class="col-md-5 d-flex">
                            <label for="auto-remove" class="form-label my-md-auto">
                                {{ __('lang.auto_remove') }}
                            </label>
                        </div>
                        <div class="col-md-7">
                            <select class="form-select my-auto" name="auto-remove" id="auto-remove">
                                <option value="" selected>{{ __('lang.select_time') }}</option>
                                <option value="5m">5 {{ __('lang.minutes') }}</option>
                                <option value="30m">30 {{ __('lang.minutes') }}</option>
                                <option value="1h">1 {{ __('lang.hour') }}</option>
                                <option value="6h">6 {{ __('lang.hours') }}</option>
                                <option value="12h">12 {{ __('lang.hours') }}</option>
                                <option value="1d">1 {{ __('lang.day') }}</option>
                                <option value="1w">1 {{ __('lang.week') }}</option>
                                <option value="1m">1 {{ __('lang.month') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="settings-container row p-2">
                        <div class="col-md-5 d-flex">
                            <label for="password" class="form-label my-md-auto">
                                {{ __('lang.set_password') }}
                            </label>
                        </div>
                        <div class="col-md-7">
                            <input 
                                type="text" 
                                class="form-control my-auto" 
                                id="password" 
                                name="password" 
                                placeholder="{{ __('lang.password') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button 
                        type="button" 
                        class="btn btn-color-4 modal-cancel" 
                        data-modal-id="settings-modal" 
                        data-bs-dismiss="modal">{{ __('lang.reset_settings') }}</button>
                    <button 
                        type="button" 
                        class="save-settings btn btn-color-1" 
                        data-current-form="#settings-form" 
                        data-bs-dismiss="modal">
                        {{ __('lang.apply_settings') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>