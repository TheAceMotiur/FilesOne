<section class="file-area">
    <div class="medium-container container {{ $randomClass }}">
        @if (downloadSetting('top_area'))
            <div class="ad-box mb-2">
                {!! downloadSetting('top_area') !!}
            </div>
        @endif
        <div class="file-preview text-center">
            <div class="file-preview-inner d-flex flex-column justify-content-center align-items-center p-4 mx-auto">
                <p class="user-select-none m-0{{ $fileExist ? '' : ' file-deleted' }}">
                    {!! $fileExist ? '' : '<span class="file-404-title d-block">404</span>' !!}
                    {{ $fileExist ? "{$file->short_key}.{$file->filetype}" : __('lang.file_deleted') }}
                </p>
                @if ($fileExist)
                    <span id="download-counter" class="user-select-none pe-none mt-2 mx-auto d-none">
                        {{ downloadSetting('countdown') }}
                    </span>
                    <a 
                        href="#" 
                        class="download-file btn btn-color-1 mt-3{{ setting('recaptcha_status') == 1 ? ' disabled' : '' }}" 
                        id="get-link" 
                        target="_blank" 
                        data-id="{!! $fileId !!}" 
                        data-key="{{ $file->short_key }}" 
                        data-slug="{{ pageSlug('file') }}" 
                        data-source="">
                        <span class="pe-none">{{ __('lang.download') }}</span>
                        <span class="pe-none d-none">
                            <span class="spinner-border spinner-border-sm text-light" role="status"></span> 
                            {{ __('lang.file_prepared') }} 
                        </span>
                        <span class="pe-none d-none">{{ __('lang.file_ready') }}</span>
                    </a>
                    @if (setting('recaptcha_status') == 1)
                        {!! RecaptchaV3::field('getsource') !!}
                        <p class="recaptcha mt-3">
                            {!! __('lang.recaptcha') !!}
                        </p>
                    @endif
                @endif
            </div>
        </div>
        @if (downloadSetting('middle_area'))
            <div class="ad-box mt-2">
                {!! downloadSetting('middle_area') !!}
            </div>
        @endif
        <div class="row d-flex mt-5">
            <div class="col-md-9 mx-auto">
                <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-0 mb-5">
                    <div class="d-flex gap-2 align-items-center">
                        <div class="dropdown">
                            <button type="button" class="btn btn-action btn-share dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Share">
                                <i class="fa-solid fa-share-nodes fa-fw pe-none"></i>
                                <span class="pe-none">{{ __('lang.share') }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                @php
                                    $currentUrl = LaravelLocalization::localizeUrl(
                                        pageSlug('file', true) . "/" . "{$file->short_key}"
                                    )
                                @endphp
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ $currentUrl }}" 
                                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;">{{ __('lang.share_on') }} Facebook</a>
                                </li>
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        href="https://x.com/share?url={{ $currentUrl }}" 
                                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;">{{ __('lang.share_on') }} X</a>
                                </li>
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        href="https://www.linkedin.com/shareArticle?url={{ $currentUrl }}" 
                                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;">{{ __('lang.share_on') }} Linkedin</a>
                                </li>
                                <li>
                                    <a 
                                        class="dropdown-item" 
                                        href="whatsapp://send?text={{ $currentUrl }}" 
                                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" >{{ __('lang.share_on') }} WhatsApp</a>
                                </li>
                                <li>
                                    <a 
                                        class="dropdown-item copy-this" 
                                        href="#"
                                        data-copy="{{ $currentUrl }}">{{ __('lang.copy') }} URL</a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <button type="button" class="btn btn-action" aria-label="Report" 
                                data-bs-toggle="modal" data-bs-target="#report-file">
                                <i class="fa-solid fa-flag fa-fw pe-none"></i>
                                <span class="pe-none">{{ __('lang.report') }}</span>
                            </button>
                        </div>
                    </div>
                    <div class="file-info d-flex flex-column ms-sm-auto">
                        <p class="text-sm-end m-0">
                            {{ $analytics[0] }} {{ __('lang.pageviews') }} | {{ $analytics[1] }} {{ __('lang.visitors') }}
                        </p>
                        <p class="text-sm-end m-0">
                            {{ $analytics[2] }} {{ __('lang.downloads') }}
                        </p>
                        <p class="text-sm-end m-0">
                            {{ __('lang.uploaded_on') }} {{ dateFormat($file->created_at, 'Y-m-d') }}
                        </p>
                    </div>
                </div>
                <div class="file-input mb-3">
                    <label for="file-link" class="form-label">{{ __('lang.file_link') }}</label>
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control user-select-all" 
                            id="file-link" 
                            value="{{ $currentUrl }}">
                        <button 
                            class="btn copy-this" 
                            type="button" 
                            data-copy="{{ $currentUrl }}">{{ __('lang.copy') }}
                        </button>
                    </div>
                </div>
                <div class="file-input mb-3">
                    <label for="html" class="form-label">HTML</label>
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control user-select-all" 
                            id="html" 
                            value="{{ fileHtml($file->short_key, $file->filetype) }}">
                        <button 
                            class="btn copy-this" 
                            type="button" 
                            data-copy="{{ fileHtml($file->short_key, $file->filetype) }}">{{ __('lang.copy') }}
                        </button>
                    </div>
                </div>
                <div class="file-input">
                    <label for="bbcode" class="form-label">BBCode</label>
                    <div class="input-group">
                        <input 
                            type="text" 
                            class="form-control user-select-all" 
                            id="bbcode" 
                            value="{{ fileBBCode($file->short_key, $file->filetype) }}">
                        <button 
                            class="btn copy-this" 
                            type="button" 
                            data-copy="{{ fileBBCode($file->short_key, $file->filetype) }}">{{ __('lang.copy') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @if (downloadSetting('bottom_area'))
            <div class="ad-box mt-5">
                {!! downloadSetting('bottom_area') !!}
            </div>
        @endif
    </div>
</section>