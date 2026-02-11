<div class="offcanvas offcanvas-start" tabindex="-1" id="side-menu">
    <div class="offcanvas-header">
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="nav">
            <div class="nav-item">
                <a 
                    class="nav-link{{ active($sidebar, ['overview']) }}" 
                    href="{{ LaravelLocalization::localizeUrl('/admin/overview') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-house fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.overview') }}
                    </span>
                </a>
            </div>
            <div class="nav-item has-submenu">
                <a class="nav-link{{ active($sidebar, ['users_add','users_all']) }}"
                    href="#">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-user-group fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.users') }}
                        <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
                    </span>
                </a>
                <div
                    class="submenu collapse{{ active($sidebar, ['users_add','users_all'], true) }}">
                    <div>
                        <a class="nav-link{{ active($sidebar, ['users_add']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/users/add') }}">{{ __('lang.add') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['users_all']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/users') }}">{{ __('lang.all') }}</a>
                    </div>
                </div>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['files_all','files_reports']) }}"
                    href="#">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-folder-open fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.files') }} {!! fileReportsNotifier() !!}
                        <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
                    </span>
                </a>
                <div
                    class="submenu collapse{{ active($sidebar, ['files_all','files_reports'], true) }}">
                    <div>
                        <a class="nav-link{{ active($sidebar, ['files_all']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/files/all') }}">{{ __('lang.all') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['files_reports']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/files/reports') }}">
                            {{ __('lang.reports') }} {!! fileReportsNotifier() !!}
                        </a>
                    </div>
                </div>
            </div>
            <div class="nav-item has-submenu">
                <a class="nav-link{{ active($sidebar, ['pages_default','pages_custom']) }}" href="#">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-layer-group fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.pages') }}
                        <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
                    </span>
                </a>
                <div class="submenu collapse{{ active($sidebar, ['pages_default','pages_custom'], true) }}">
                    <div>
                        <a class="nav-link{{ active($sidebar, ['pages_default']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/pages/default') }}">{{ __('lang.default') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['pages_custom']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/pages/custom') }}">{{ __('lang.custom') }}</a>
                    </div>
                </div>
            </div>
            <div class="nav-item has-submenu">
                <a class="nav-link{{ active($sidebar, ['blog_posts','blog_categories','blog_comments']) }}" href="#">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-pen-to-square fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.blog') }}
                        <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
                    </span>
                </a>
                <div
                    class="submenu collapse{{ active($sidebar, ['blog_posts','blog_categories','blog_comments'], true) }}">
                    <div>
                        <a class="nav-link{{ active($sidebar, ['blog_posts']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/blog/posts') }}">{{ __('lang.posts') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['blog_categories']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/blog/categories') }}">{{ __('lang.categories') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['blog_comments']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/blog/comments') }}">{{ __('lang.comments') }}</a>
                    </div>
                </div>
            </div>
            <div class="nav-item has-submenu">
                <a class="nav-link{{ active($sidebar, ['payments_settings','payments_plans','payments_logs']) }}"
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
                    class="submenu collapse{{ active($sidebar, ['payments_settings','payments_plans','payments_logs'], true) }}">
                    <div>
                        <a class="nav-link{{ active($sidebar, ['payments_settings']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/payments/settings') }}">{{ __('lang.settings') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['payments_plans']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/payments/plans') }}">{{ __('lang.plans') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['payments_logs']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/payments/logs') }}">{{ __('lang.logs') }}</a>
                    </div>
                </div>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['website_settings','footer_settings','admin_settings','storage_settings','download_settings']) }}"
                    href="#">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-gear fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.settings') }}
                        <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
                    </span>
                </a>
                <div
                    class="submenu collapse{{ active($sidebar, ['website_settings','footer_settings','admin_settings','storage_settings','download_settings'], true) }}">
                    <div>
                        <a class="nav-link{{ active($sidebar, ['website_settings']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/settings/website') }}">{{ __('lang.website') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['footer_settings']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/settings/footer') }}">{{ __('lang.footer') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['admin_settings']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/settings/admin') }}">{{ __('lang.admin') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['storage_settings']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/settings/storage') }}">{{ __('lang.storage') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['download_settings']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/settings/download') }}">{{ __('lang.download') }}</a>
                    </div>
                </div>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['affiliate_settings','affiliate_statistics','affiliate_users','affiliate_payout_rates','affiliate_withdrawal_methods']) }}"
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
                    class="submenu collapse{{ active($sidebar, ['affiliate_settings','affiliate_statistics','affiliate_users','affiliate_payout_rates','affiliate_withdrawal_methods'], true) }}">
                    <div>
                        <a class="nav-link{{ active($sidebar, ['affiliate_settings']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/settings') }}">{{ __('lang.settings') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['affiliate_statistics']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/statistics') }}">{{ __('lang.statistics') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['affiliate_users']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/users') }}">{{ __('lang.users') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['affiliate_payout_rates']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/payout-rates') }}">{{ __('lang.payout_rates') }}</a>
                    </div>
                    <div>
                        <a class="nav-link{{ active($sidebar, ['affiliate_withdrawal_methods']) }}"
                            href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/withdrawal-methods') }}">{{ __('lang.withdrawal_methods') }}</a>
                    </div>
                </div>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['withdrawals']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/withdrawals') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-money-bill-1 fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.withdrawals') }}
                    </span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['emails']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/emails') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-envelope fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.emails') }}
                    </span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['language']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/language') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-language fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.language') }}
                    </span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['logs']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/logs') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-clock-rotate-left fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.logs') }}
                    </span>
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link{{ active($sidebar, ['subscribers']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/subscribers') }}">
                    <span class="sidebar-i pe-none">
                        <i class="fa-solid fa-envelope-open-text fa-fw"></i>
                    </span>
                    <span class="sidebar-t ms-3 pe-none">
                        {{ __('lang.subscribers') }}
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
        <a class="nav-link{{ active($sidebar, ['overview']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/overview') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-house fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.overview') }}
            </span>
        </a>
    </div>
    <div class="nav-item has-submenu">
        <a class="nav-link{{ active($sidebar, ['users_add','users_all']) }}"
            href="#">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-user-group fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.users') }}
                <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
            </span>
        </a>
        <div
            class="submenu collapse{{ active($sidebar, ['users_add','users_all'], true) }}">
            <div>
                <a class="nav-link{{ active($sidebar, ['users_add']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/users/add') }}">{{ __('lang.add') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['users_all']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/users') }}">{{ __('lang.all') }}</a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['files_all','files_reports']) }}"
            href="#">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-folder-open fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.files') }} {!! fileReportsNotifier() !!}
                <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
            </span>
        </a>
        <div
            class="submenu collapse{{ active($sidebar, ['files_all','files_reports'], true) }}">
            <div>
                <a class="nav-link{{ active($sidebar, ['files_all']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/files/all') }}">{{ __('lang.all') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['files_reports']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/files/reports') }}">
                    {{ __('lang.reports') }} {!! fileReportsNotifier() !!}
                </a>
            </div>
        </div>
    </div>
    <div class="nav-item has-submenu">
        <a class="nav-link{{ active($sidebar, ['pages_default','pages_custom']) }}" href="#">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-layer-group fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.pages') }}
                <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
            </span>
        </a>
        <div class="submenu collapse{{ active($sidebar, ['pages_default','pages_custom'], true) }}">
            <div>
                <a class="nav-link{{ active($sidebar, ['pages_default']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/pages/default') }}">{{ __('lang.default') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['pages_custom']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/pages/custom') }}">{{ __('lang.custom') }}</a>
            </div>
        </div>
    </div>
    <div class="nav-item has-submenu">
        <a class="nav-link{{ active($sidebar, ['blog_posts','blog_categories','blog_comments']) }}" href="#">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-pen-to-square fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.blog') }}
                <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
            </span>
        </a>
        <div
            class="submenu collapse{{ active($sidebar, ['blog_posts','blog_categories','blog_comments'], true) }}">
            <div>
                <a class="nav-link{{ active($sidebar, ['blog_posts']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/blog/posts') }}">{{ __('lang.posts') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['blog_categories']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/blog/categories') }}">{{ __('lang.categories') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['blog_comments']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/blog/comments') }}">{{ __('lang.comments') }}</a>
            </div>
        </div>
    </div>
    <div class="nav-item has-submenu">
        <a class="nav-link{{ active($sidebar, ['payments_settings','payments_plans','payments_logs']) }}"
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
            class="submenu collapse{{ active($sidebar, ['payments_settings','payments_plans','payments_logs'], true) }}">
            <div>
                <a class="nav-link{{ active($sidebar, ['payments_settings']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/payments/settings') }}">{{ __('lang.settings') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['payments_plans']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/payments/plans') }}">{{ __('lang.plans') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['payments_logs']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/payments/logs') }}">{{ __('lang.logs') }}</a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['website_settings','footer_settings','admin_settings','storage_settings','download_settings']) }}"
            href="#">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-gear fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.settings') }}
                <i class="fa-solid fa-angle-down fa-sm my-auto ms-auto"></i>
            </span>
        </a>
        <div
            class="submenu collapse{{ active($sidebar, ['website_settings','footer_settings','admin_settings','storage_settings','download_settings'], true) }}">
            <div>
                <a class="nav-link{{ active($sidebar, ['website_settings']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/settings/website') }}">{{ __('lang.website') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['footer_settings']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/settings/footer') }}">{{ __('lang.footer') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['admin_settings']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/settings/admin') }}">{{ __('lang.admin') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['storage_settings']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/settings/storage') }}">{{ __('lang.storage') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['download_settings']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/settings/download') }}">{{ __('lang.download') }}</a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['affiliate_settings','affiliate_statistics','affiliate_users','affiliate_payout_rates','affiliate_withdrawal_methods']) }}"
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
            class="submenu collapse{{ active($sidebar, ['affiliate_settings','affiliate_statistics','affiliate_users','affiliate_payout_rates','affiliate_withdrawal_methods'], true) }}">
            <div>
                <a class="nav-link{{ active($sidebar, ['affiliate_settings']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/settings') }}">{{ __('lang.settings') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['affiliate_statistics']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/statistics') }}">{{ __('lang.statistics') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['affiliate_users']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/users') }}">{{ __('lang.users') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['affiliate_payout_rates']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/payout-rates') }}">{{ __('lang.payout_rates') }}</a>
            </div>
            <div>
                <a class="nav-link{{ active($sidebar, ['affiliate_withdrawal_methods']) }}"
                    href="{{ LaravelLocalization::localizeUrl('/admin/affiliate/withdrawal-methods') }}">{{ __('lang.withdrawal_methods') }}</a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['withdrawals']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/withdrawals') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-money-bill-1 fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.withdrawals') }}
            </span>
        </a>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['emails']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/emails') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-envelope fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.emails') }}
            </span>
        </a>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['language']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/language') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-language fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.language') }}
            </span>
        </a>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['logs']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/logs') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-clock-rotate-left fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.logs') }}
            </span>
        </a>
    </div>
    <div class="nav-item">
        <a class="nav-link{{ active($sidebar, ['subscribers']) }}" href="{{ LaravelLocalization::localizeUrl('/admin/subscribers') }}">
            <span class="sidebar-i pe-none">
                <i class="fa-solid fa-envelope-open-text fa-fw"></i>
            </span>
            <span class="sidebar-t ms-3 pe-none">
                {{ __('lang.subscribers') }}
            </span>
        </a>
    </div>
</div>