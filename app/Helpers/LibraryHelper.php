<?php

/**
 * Generate js files for a page
 * @param string $pageKey
 * @return string
 */
function loadJS(
    string $pageKey
): string {

    $libraries = '';

    if ($pageKey == 'home') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/dropzone/dropzone.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/uploader.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'contact') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'pricing') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'pay') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'affiliate') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'blog') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'blog_inner') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'login') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'register') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'forgot_password') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'verify_account') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'terms_of_use' || $pageKey == 'privacy_policy' || $pageKey == 'custom_page') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'file') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == '403' || $pageKey == '404' || $pageKey == '500' || $pageKey == 'maintenance') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
        $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

        return $libraries;
    }
    
    $libraries .= library('assets/plugin/bootstrap/bootstrap.bundle.min.js') . PHP_EOL;
    $libraries .= library('assets/plugin/toastify/toastify.min.js') . PHP_EOL;
    $libraries .= library('assets/plugin/lazyload/lazyload.min.js') . PHP_EOL;
    $libraries .= library('assets/plugin/wow/wow.min.js') . PHP_EOL;
    $libraries .= library('assets/plugin/smooth-scroll/SmoothScroll.min.js') . PHP_EOL;
    $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.js') . PHP_EOL;
    $libraries .= library('assets/js/frontend/frontend.min.js') . PHP_EOL;
    $libraries .= library('assets/js/frontend/cookie.min.js') . PHP_EOL;

    return $libraries;
}

/**
 * Generate css files for a page
 * @param string $pageKey
 * @return string
 */
function loadCSS(
    string $pageKey
): string {

    $libraries = '';

    if ($pageKey == 'home') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/dropzone/dropzone.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'contact') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'pricing') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'pay') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'affiliate') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'blog') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'blog_inner') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'login') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'register') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'forgot_password') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'verify_account') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'terms_of_use' || $pageKey == 'privacy_policy' || $pageKey == 'custom_page') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == 'file') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }

    if ($pageKey == '403' || $pageKey == '404' || $pageKey == '500' || $pageKey == 'maintenance') {
        if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
        } else {
            $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
            $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
        }
        $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
        $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

        return $libraries;
    }
    
    if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.rtl.min.css', true) . PHP_EOL;
        $libraries .= library('assets/css/main.rtl.min.css', true) . PHP_EOL;
    } else {
        $libraries .= library('assets/plugin/bootstrap/bootstrap.min.css', true) . PHP_EOL;
        $libraries .= library('assets/css/main.min.css', true) . PHP_EOL;
    }
    $libraries .= library('assets/plugin/toastify/toastify.min.css') . PHP_EOL;
    $libraries .= library('assets/plugin/fontawesome/css/all.min.css') . PHP_EOL;
    $libraries .= library('assets/plugin/flag-icons/flag-icons.min.css') . PHP_EOL;
    $libraries .= library('assets/plugin/animate/animate.min.css') . PHP_EOL;
    $libraries .= library('assets/plugin/overlayscrollbars/overlayscrollbars.min.css') . PHP_EOL;

    return $libraries;
}

/**
 * Generate Google fonts
 * @return string
 */
function loadFonts(): string {
    $fonts = '';

    $fonts .= '<link rel="preconnect" href="https://fonts.googleapis.com">' . PHP_EOL;
    $fonts .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . PHP_EOL;
    $fonts .= '<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@'
        . '0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">' . PHP_EOL;

    return $fonts;
}