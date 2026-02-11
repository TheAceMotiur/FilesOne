<div class="offcanvas offcanvas-start" tabindex="-1" id="side-menu">
    <div class="offcanvas-header">
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="nav">
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['overview']) }}" href="{{ LaravelLocalization::localizeUrl('/user/overview') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-house fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.overview') }}
                    </span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['files']) }}" href="{{ LaravelLocalization::localizeUrl('/user/files') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-folder-open fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.files') }}
                    </span>
                </a>
            </div>
            @if (affiliateSetting('status') == 1)
                <div class="nav-item">
                    <a class="nav-link{{ active($sidebar, ['statistics','withdrawal']) }}"
                        href="#">
                        <span class="sidebar-i pe-none">
                            <i class="fa-solid fa-sack-dollar fa-fw"></i>
                        </span>
                        <span class="sidebar-t ms-3 pe-none">
                            {{ __('lang.affiliate') }}
                            <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
                        </span>
                    </a>
                    <div
                        class="submenu collapse{{ active($sidebar, ['statistics','withdrawal'], true) }}">
                        <div>
                            <a class="nav-link{{ active($sidebar, ['statistics']) }}"
                                href="{{ LaravelLocalization::localizeUrl('/user/affiliate/statistics') }}">{{ __('lang.statistics') }}</a>
                        </div>
                        <div>
                            <a class="nav-link{{ active($sidebar, ['withdrawal']) }}"
                                href="{{ LaravelLocalization::localizeUrl('/user/affiliate/withdrawal') }}">{{ __('lang.withdrawal') }}</a>
                        </div>
                    </div>
                </div>
            @endif
            <div class="nav-item has-submenu">
                <a class="nav-link{{ active($sidebar, ['payments_all','payments_plan']) }}"
                    href="#">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-credit-card fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.payments') }}
                        <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
                    </span>
                </a>
                <div
                    class="submenu collapse{{ active($sidebar, ['payments_all','payments_plan'], true) }}">
                    <div>
                        <a class="nav-link{{ active($sidebar, ['payments_all']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/user/payments/all') }}">{{ __('lang.all') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['payments_plan']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/user/payments/plan') }}">{{ __('lang.plan') }}</a>
                    </div>
                </div>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['settings']) }}" href="{{ LaravelLocalization::localizeUrl('/user/settings') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-gear fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.settings') }}
                    </span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['api']) }}" href="{{ LaravelLocalization::localizeUrl('/user/api') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-circle-nodes fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        API
                    </span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['logs']) }}" href="{{ LaravelLocalization::localizeUrl('/user/logs') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-clock-rotate-left fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.logs') }}
                    </span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ LaravelLocalization::localizeUrl("/user/upload") }}" class="upload-button nav-link btn-color-1 w-100 d-flex">
                    <span class="sidebar-i pe-none m-auto d-none">
                        <i class="fa-solid fa-gear fa-fw"></i>
                    </span>
                    <span class="sidebar-upload-t mx-auto">
                        {{ __('lang.upload_file') }}
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="sidebar-logo d-none d-xl-flex">
    @if (session('color'))
        @if (session('color') == 'dark')
            <img id="sidebar-logo-img" src="{{ img('other', setting('logo_light')) }}" alt="Logo">
        @else
            <img id="sidebar-logo-img" src="{{ img('other', setting('logo_dark')) }}" alt="Logo">
        @endif
    @else
        @if (setting('default_color_mode') == 2)
            <img id="sidebar-logo-img" src="{{ img('other', setting('logo_light')) }}" alt="Logo">
        @else
            <img id="sidebar-logo-img" src="{{ img('other', setting('logo_dark')) }}" alt="Logo">
        @endif
    @endif
    <button type="button" class="action-button btn btn-sm layout-changer" data-bg="gray" aria-label="Sidebar collapse" 
        data-collapsed="{{ session('sidebar-collapsed') ? session('sidebar-collapsed') : 0 }}">
        <i class="fa-solid fa-bars-staggered fa-fw"></i>
    </button>
</div>
<div class="nav d-none d-xl-flex pb-5">
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['overview']) }}" href="{{ LaravelLocalization::localizeUrl('/user/overview') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-house fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.overview') }}
            </span>
        </a>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['files']) }}" href="{{ LaravelLocalization::localizeUrl('/user/files') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-folder-open fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.files') }}
            </span>
        </a>
    </div>
    @if (affiliateSetting('status') == 1)
        <div class="nav-item">
            <a class="nav-link{{ active($sidebar, ['statistics','withdrawal']) }}"
                href="#">
                <span class="sidebar-i pe-none">
                    <i class="fa-solid fa-sack-dollar fa-fw"></i>
                </span>
                <span class="sidebar-t ms-3 pe-none">
                    {{ __('lang.affiliate') }}
                    <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
                </span>
            </a>
            <div
                class="submenu collapse{{ active($sidebar, ['statistics','withdrawal'], true) }}">
                <div>
                    <a class="nav-link{{ active($sidebar, ['statistics']) }}"
                        href="{{ LaravelLocalization::localizeUrl('/user/affiliate/statistics') }}">{{ __('lang.statistics') }}</a>
                </div>
                <div>
                    <a class="nav-link{{ active($sidebar, ['withdrawal']) }}"
                        href="{{ LaravelLocalization::localizeUrl('/user/affiliate/withdrawal') }}">{{ __('lang.withdrawal') }}</a>
                </div>
            </div>
        </div>
    @endif
    <div class="nav-item has-submenu">
        <a class="nav-link{{ active($sidebar, ['payments_all','payments_plan']) }}"
            href="#">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-credit-card fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.payments') }}
                <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
            </span>
        </a>
        <div
            class="submenu collapse{{ active($sidebar, ['payments_all','payments_plan'], true) }}">
            <div>
                <a class="nav-link{{ active($sidebar, ['payments_all']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/user/payments/all') }}">{{ __('lang.all') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['payments_plan']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/user/payments/plan') }}">{{ __('lang.plan') }}</a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['settings']) }}" href="{{ LaravelLocalization::localizeUrl('/user/settings') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-gear fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.settings') }}
            </span>
        </a>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['api']) }}" href="{{ LaravelLocalization::localizeUrl('/user/api') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-circle-nodes fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                API
            </span>
        </a>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['logs']) }}" href="{{ LaravelLocalization::localizeUrl('/user/logs') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-clock-rotate-left fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.logs') }}
            </span>
        </a>
    </div>
    <div class="nav-item">
        <a href="{{ LaravelLocalization::localizeUrl("/user/upload") }}" class="upload-button nav-link btn-color-1 d-flex">
            @if (session('sidebar-collapsed') == 1)
                <span class="sidebar-upload-i pe-none m-auto">
                    <i class="fa-solid fa-up-long fa-fw fa-sm"></i>
                </span>
                <span class="sidebar-upload-t mx-auto d-none">
                    {{ __('lang.upload_file') }}
                </span>
            @else
                <span class="sidebar-upload-i pe-none m-auto d-none">
                    <i class="fa-solid fa-up-long fa-fw fa-sm"></i>
                </span>
                <span class="sidebar-upload-t mx-auto">
                    {{ __('lang.upload_file') }}
                </span>
            @endif
        </a>
    </div>
</div>