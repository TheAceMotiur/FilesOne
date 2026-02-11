<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OverviewController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\FilesController;
use App\Http\Controllers\Admin\PagesDefaultController;
use App\Http\Controllers\Admin\PagesCustomController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoriesController;
use App\Http\Controllers\Admin\BlogCommentsController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\SettingsWebsiteController;
use App\Http\Controllers\Admin\SettingsFooterController;
use App\Http\Controllers\Admin\SettingsAdminController;
use App\Http\Controllers\Admin\SettingsStorageController;
use App\Http\Controllers\Admin\SettingsDownloadController;
use App\Http\Controllers\Admin\AffiliateController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\EmailsController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\SubscribersController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Admin\HelperController;

Route::permanentRedirect('/', url('/admin/overview'));

Route::controller(OverviewController::class)
    ->prefix('overview')
    ->group(function (): void {
        Route::get('/', 'overview')->name('admin.overview');
        Route::post('/registration-analytics', 'registrationAnalytics');
        Route::post('/file-types-analytics', 'fileTypesAnalytics');
        Route::post('/quota-analytics', 'quotaAnalytics');
        Route::post('/top-cards', 'topCards');
    });

Route::controller(UsersController::class)
    ->prefix('users')
    ->group(function (): void {
        Route::get('/', 'all');
        Route::post('/', 'all_post');
        Route::get('/add', 'add');
        Route::post('/add', 'add_post');
        Route::get('/edit/{id}', 'edit');
        Route::post('/edit/{id}', 'edit_post');
        Route::post('/delete/{id}', 'delete');
    });

Route::controller(FilesController::class)
    ->prefix('files')
    ->group(function (): void {
        Route::prefix('all')->group(function () {
            Route::get('/', 'all');
            Route::post('/', 'all_post');
            Route::post('/get', 'all_view_post');
            Route::get('/download/{filekey}', 'all_download_post');
            Route::post('/delete/{filekey}', 'all_delete_post');
            Route::post('/stats', 'all_stats_post');
        });
        Route::prefix('reports')->group(function () {
            Route::get('/', 'reports');
            Route::post('/', 'reports_post');
            Route::post('/get/{reportId}', 'reports_get_post');
            Route::post('/delete/{reportId}', 'reports_delete_post');
        });
    });

Route::permanentRedirect('/pages', url('/admin/pages/default/all'));

Route::controller(PagesDefaultController::class)
    ->prefix('pages')
    ->group(function (): void {
        Route::prefix('default')->group(function (): void {
            Route::get('/', 'all');
            Route::post('/', 'all_post');
            Route::get('/edit/{id}', 'edit');
            Route::post('/edit/{id}', 'edit_post');
            Route::post('/upload-file', 'upload_file');
            Route::post('/delete-file', 'delete_file');
        });
    });

Route::controller(PagesCustomController::class)
    ->prefix('pages')
    ->group(function (): void {
        Route::prefix('custom')->group(function (): void {
            Route::get('/', 'all');
            Route::post('/', 'all_post');
            Route::get('/add', 'add');
            Route::post('/add', 'add_post');
            Route::get('/edit/{id}', 'edit');
            Route::post('/edit/{id}', 'edit_post');
            Route::post('/delete/{id}', 'delete');
            Route::post('/upload-file', 'upload_file');
            Route::post('/delete-file', 'delete_file');
        });
    });

Route::permanentRedirect('/blog', url('/admin/blog/post/all'));

Route::controller(BlogController::class)
    ->prefix('blog')
    ->group(function (): void {
        Route::prefix('posts')->group(function (): void {
            Route::get('/', 'all');
            Route::post('/', 'all_post');
            Route::get('/add', 'add');
            Route::post('/add', 'add_post');
            Route::get('/edit/{id}', 'edit');
            Route::post('/edit/{id}', 'edit_post');
            Route::post('/delete/{id}', 'delete');
            Route::post('/upload-file', 'upload_file');
            Route::post('/delete-file', 'delete_file');
        });
    });

Route::controller(BlogCategoriesController::class)
    ->prefix('blog')
    ->group(function (): void {
        Route::prefix('categories')->group(function (): void {
            Route::get('/', 'all');
            Route::post('/', 'all_post');
            Route::get('/add', 'add');
            Route::post('/add', 'add_post');
            Route::get('/edit/{id}', 'edit');
            Route::post('/edit/{id}', 'edit_post');
            Route::post('/delete/{id}', 'delete');
        });
    });

Route::controller(BlogCommentsController::class)
    ->prefix('blog')
    ->group(function (): void {
        Route::prefix('comments')->group(function () {
            Route::get('/', 'all');
            Route::post('/', 'all_post');
            Route::post('/verify/{id}', 'verify');
            Route::post('/delete/{id}', 'delete');
        });
    });

Route::controller(PaymentsController::class)
    ->prefix('payments')
    ->group(function (): void {
        Route::prefix('settings')->group(function (): void {
            Route::get('/', 'settings');
            Route::post('/', 'settings_post');
            Route::post('/bank', 'settings_post_bank');
            Route::post('/stripe', 'settings_post_stripe');
            Route::post('/razorpay', 'settings_post_razorpay');
        });
        Route::prefix('plans')->group(function (): void {
            Route::get('/', 'plans');
            Route::post('/', 'plans_post');
            Route::get('/add', 'plans_add');
            Route::post('/add', 'plans_add_post');
            Route::get('/edit/{id}', 'plans_edit');
            Route::post('/edit/{id}', 'plans_edit_post');
            Route::post('/delete/{id}', 'plans_delete');
        });
        Route::prefix('logs')->group(function (): void {
            Route::get('/', 'logs');
            Route::post('/', 'logs_post');
            Route::post('/get/{id}', 'logs_single_post');
            Route::post('/verify/{id}', 'logs_verify');
            Route::post('/reject/{id}', 'logs_reject');
        });
    });

Route::permanentRedirect('/settings', url('/admin/settings/website'));

Route::controller(SettingsWebsiteController::class)
    ->prefix('settings')
    ->group(function (): void {
        Route::get('/website', 'website');
        Route::post('/website', 'settings_post_website');
        Route::post('/logo', 'settings_post_logo');
        Route::post('/preferences', 'settings_post_preferences');
        Route::post('/contact', 'settings_post_contact');
        Route::post('/auth', 'settings_post_auth');
        Route::post('/recaptcha', 'settings_post_recaptcha');
        Route::post('/ip2location', 'settings_post_ip2location');
        Route::post('/additional', 'settings_post_additional');
        Route::post('/cache', 'settings_post_cache');
    });

Route::controller(SettingsFooterController::class)
    ->prefix('settings')
    ->group(function (): void {
        Route::prefix('footer')->group(function (): void {
            Route::get('/', 'footer');
            Route::post('/', 'footer_post');
        });
    });

Route::controller(SettingsAdminController::class)
    ->prefix('settings')
    ->group(function (): void {
        Route::prefix('admin')->group(function (): void {
            Route::get('/', 'admin');
            Route::post('/', 'admin_post');
        });
    });

Route::controller(SettingsStorageController::class)
    ->prefix('settings')
    ->group(function (): void {
        Route::prefix('storage')->group(function (): void {
            Route::get('/', 'storage');
            Route::post('/', 'storage_post');
            Route::post('/s3', 'storage_s3_post');
            Route::post('/r2', 'storage_r2_post');
            Route::post('/wasabi', 'storage_wasabi_post');
            Route::post('/ftp', 'storage_ftp_post');
            Route::post('/google/{id}', 'storage_google_post');
            Route::get('/google/add', 'storage_google_add');
            Route::post('/google/add', 'storage_google_add_post');
            Route::post('/google/delete/{id}', 'storage_google_delete');
            Route::post('/google/test/{id}', 'storage_google_test');
            Route::post('/temp-files', 'temp_files');
        });
    });

Route::controller(SettingsDownloadController::class)
    ->prefix('settings')
    ->group(function (): void {
        Route::prefix('download')->group(function (): void {
            Route::get('/', 'download');
            Route::post('/', 'download_post');
            Route::post('/ads', 'ads_post');
        });
    });

Route::controller(AffiliateController::class)
    ->prefix('affiliate')
    ->group(function (): void {
        Route::prefix('settings')->group(function (): void {
            Route::get('/', 'settings');
            Route::post('/', 'settings_post');
        });
        Route::prefix('statistics')->group(function (): void {
            Route::get('/', 'statistics');
            Route::post('/', 'statistics_post');
        });
        Route::prefix('users')->group(function (): void {
            Route::get('/', 'users');
            Route::post('/', 'users_post');
        });
        Route::prefix('payout-rates')->group(function (): void {
            Route::get('/', 'payout_rates');
            Route::post('/', 'payout_rates_data');
            Route::get('/add', 'payout_rates_add');
            Route::post('/add', 'payout_rates_add_post');
            Route::get('/edit/{id}', 'payout_rates_edit');
            Route::post('/edit/{id}', 'payout_rates_edit_post');
            Route::post('/delete/{id}', 'payout_rates_delete');
        });
        Route::prefix('withdrawal-methods')->group(function (): void {
            Route::get('/', 'withdrawal_methods');
            Route::post('/', 'withdrawal_methods_data');
            Route::get('/add', 'withdrawal_methods_add');
            Route::post('/add', 'withdrawal_methods_add_post');
            Route::get('/edit/{id}', 'withdrawal_methods_edit');
            Route::post('/edit/{id}', 'withdrawal_methods_edit_post');
            Route::post('/delete/{id}', 'withdrawal_methods_delete');
        });
    });

Route::controller(WithdrawalController::class)
    ->prefix('withdrawals')
    ->group(function (): void {
        Route::get('/', 'withdrawals');
        Route::post('/', 'withdrawals_post');
        Route::post('/get/{id}', 'withdrawals_single_post');
        Route::post('/verify/{id}', 'withdrawals_verify');
        Route::post('/reject/{id}', 'withdrawals_reject');
    });

Route::controller(EmailsController::class)
    ->prefix('emails')
    ->group(function (): void {
        Route::get('/', 'emails');
        Route::post('/', 'emails_post');
        Route::prefix('contents')->group(function (): void {
            Route::post('/', 'emails_content');
            Route::get('/edit/{id}', 'email_contents_edit');
            Route::post('/edit/{id}', 'email_contents_edit_post');
        });
        Route::prefix('contact')->group(function (): void {
            Route::post('/', 'emails_logs_data');
            Route::post('/get/{id}', 'emails_logs_data_single');
            Route::post('/delete/{id}', 'emails_logs_delete');
        });
    });

Route::controller(LanguageController::class)
    ->prefix('language')
    ->group(function (): void {
        Route::get('/', 'language');
        Route::post('/', 'language_post');
        Route::post('/all', 'language_table_post');
    });

Route::controller(LogsController::class)
    ->prefix('logs')
    ->group(function (): void {
        Route::get('/', 'logs');
        Route::post('/', 'logs_post');
        Route::post('/clear', 'logs_clear_post');
    });
    
Route::controller(SubscribersController::class)
    ->prefix('subscribers')
    ->group(function (): void {
        Route::get('/', 'subscribers');
        Route::post('/', 'subscribers_send');
        Route::post('/all', 'subscribers_data');
        Route::post('/delete/{id}', 'subscribers_delete');
    });

Route::get('/logout', [AuthController::class, 'logout']);

Route::controller(HelperController::class)
    ->group(function (): void {
        Route::post('/collapse-sidebar', 'collapse_sidebar');
        Route::post('/color-mode', 'theme_mode');
    });