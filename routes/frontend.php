<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\UploadController;
use App\Http\Controllers\Frontend\DownloadController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\PricingController;
use App\Http\Controllers\Frontend\AffiliateController;
use App\Http\Controllers\Frontend\FileController;
use App\Http\Controllers\Frontend\PayController;
use App\Http\Controllers\Frontend\LegalController;
use App\Http\Controllers\Frontend\PagesController;
use App\Http\Controllers\Frontend\SubscriberController;
use App\Http\Controllers\Frontend\HelperController;
use App\Http\Controllers\Frontend\SitemapController;

Route::get('/', [HomeController::class, 'home'])->name('home');

$loginSlug = pageSlug('login') ?: 'login';
Route::controller(AuthController::class)
    ->middleware('auth.guest')
    ->group(function () use ($loginSlug) {
        Route::get("/{$loginSlug}", 'login')->name('login');
        Route::post('login', 'login_post');
        Route::get('/login/google/callback', 'login_post_google');
        Route::get('/login/google/redirect', 'login_post_google_redirect');
    });

$registerSlug = pageSlug('register') ?: 'register';
Route::controller(AuthController::class)
    ->middleware('auth.guest')
    ->group(function () use ($registerSlug) {
        Route::get("/$registerSlug", 'register')->name('register');
        Route::post('/register', 'register_post');
    });

$forgotPasswordSlug = pageSlug('forgot_password') ?: 'forgot-password';
Route::controller(AuthController::class)
    ->middleware('auth.guest')
    ->group(function () use ($forgotPasswordSlug) {
        Route::get("/{$forgotPasswordSlug}", 'forgot_password');
        Route::get("/{$forgotPasswordSlug}/{key}", 'forgot_password_reset');
        Route::post('/forgot-password', 'forgot_password_post');
    });

$verifyAccountSlug = pageSlug('verify_account') ?: 'verify-account';
Route::controller(AuthController::class)
    ->middleware('auth.guest')
    ->group(function () use ($verifyAccountSlug) {
        Route::get("/{$verifyAccountSlug}", 'verify_account');
        Route::get("/{$verifyAccountSlug}/{key}", 'verify_account_verified');
        Route::post('/verify-account', 'verify_account_post');
    });

Route::controller(UploadController::class)
    ->group(function () {
        Route::post('/upload', 'upload');
        Route::post('/upload-link', 'upload_link');
        Route::post('/get-file', 'get_file_data');
        Route::post('/fetch-file', 'fetch_file');
    });

Route::controller(DownloadController::class)
    ->group(function () {
        Route::post('/download-zip', 'get_zip');
        Route::get('/download-zip/{zipkey}', 'download_zip');
        Route::post('/download-single', 'get_file');
        Route::get('/download-single/{filekey}', 'download_file');
    });

$fileSlug = pageSlug('file') ?: 'file';
Route::controller(FileController::class)
    ->prefix($fileSlug)
    ->group(function () {
        Route::get('/clear', 'clear');
        Route::get('/{filekey}', 'file');
        Route::post('/{filekey}/get-source', 'file_get_source')
            ->middleware(['filePrivacy']);
        Route::post('/{filekey}/get-link', 'file_get_link')
            ->middleware(['filePrivacy']);
        Route::get('/{filekey}/download', 'file_download')
            ->middleware(['filePrivacy', 'signatureCheck'])
            ->name('fileDownload');
        Route::post('/{filekey}', 'file_password')
            ->middleware('throttle:filePassword');
        Route::post('/{filekey}/report', 'report')
            ->middleware('throttle:report');
    });

$blogSlug = pageSlug('blog') ?: 'blog';
$blogInnerSlug = pageSlug('blog_inner') ?: 'post';
Route::controller(BlogController::class)
    ->group(function () use ($blogSlug,$blogInnerSlug) {
        Route::get("/{$blogSlug}", 'blog');
        Route::get("/{$blogInnerSlug}/{slug}", 'inner');
        Route::post("/{$blogInnerSlug}/{slug}/new-comment", 'comment_new');
        Route::post("/{$blogSlug}/{limit}/{offset}", 'blog_posts_data');
    });

$contactSlug = pageSlug('contact') ?: 'contact';
Route::controller(ContactController::class)
    ->group(function () use ($contactSlug) {
        Route::get("/{$contactSlug}", 'contact');
        Route::post('/contact', 'contact_post');
    });

$pricingSlug = pageSlug('pricing') ?: 'pricing';
Route::controller(PricingController::class)
    ->group(function () use ($pricingSlug) {
        Route::get("/{$pricingSlug}", 'pricing');
    });

$paySlug = pageSlug('pay') ?: 'pay';
Route::controller(PayController::class)
    ->prefix($paySlug)
    ->middleware('auth.user')
    ->group(function () {
        Route::get("/{planString}", 'pay');
        Route::post("/{planString}/stripe-token", 'pay_post_stripe_token');
        Route::any("/{planString}/stripe", 'pay_post_stripe_process');
        Route::post("/{planString}/razorpay-token", 'pay_post_razorpay_order');
        Route::any("/{planString}/razorpay/{orderId}", 'pay_post_razorpay_process');
        Route::post("/{planString}/bank", 'pay_post_bank');
    });

$affiliateSlug = pageSlug('affiliate') ?: 'affiliate';
Route::controller(AffiliateController::class)
    ->group(function () use ($affiliateSlug) {
        Route::get("/{$affiliateSlug}", 'affiliate');
    });

Route::get('/p/{path}', [PagesController::class, 'loadPage']);

$termsSlug = pageSlug('terms_of_use') ?: 'terms-of-use';
$privacySlug = pageSlug('privacy_policy') ?: 'privacy-policy';
Route::controller(LegalController::class)
    ->group(function () use ($termsSlug,$privacySlug) {
        Route::get("/{$termsSlug}", 'terms_of_use');
        Route::get("/{$privacySlug}", 'privacy_policy');
    });

Route::controller(SubscriberController::class)
    ->prefix('subscribe')
    ->group(function () {
        Route::post('/', 'subscribe');
        Route::get('/{key}', 'subscribe_verify');
    });

Route::get('/logout', [AuthController::class, 'logout']);

Route::post('/color-mode', [HelperController::class, 'theme_mode']);

Route::get('/404', function () {
    abort(404);
});
Route::get('/403', function () {
    abort(403);
});
Route::get('/500', function () {
    abort(500);
});

$maintenanceSlug = pageSlug('maintenance') ?: 'maintenance';
Route::get("/{$maintenanceSlug}", [HelperController::class, 'maintenance']);

Route::get('/sitemap.xml', [SitemapController::class, 'sitemap']);

Route::get('/phpinfo', function () { 
    return phpinfo();
})->middleware('auth.admin');
