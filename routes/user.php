<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\OverviewController;
use App\Http\Controllers\User\FilesController;
use App\Http\Controllers\User\PaymentsController;
use App\Http\Controllers\User\AffiliateController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\LogsController;
use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\User\HelperController;
use App\Http\Controllers\User\UploadController;
use App\Http\Controllers\User\ApiController;

Route::permanentRedirect('/', url('/user/overview'));

Route::controller(OverviewController::class)
    ->prefix('overview')
    ->group(function (): void {
        Route::get('/', 'overview')->name('user.overview');
        Route::post('/file-types-analytics', 'fileTypesAnalytics');
        Route::post('/quota-analytics', 'quotaAnalytics');
        Route::post('/top-cards', 'topCards');
        Route::post('/visitor-analytics','visitorAnalytics');
    });

Route::controller(FilesController::class)
    ->prefix('files')
    ->group(function (): void {
        Route::get('/', 'all');
        Route::post('/', 'all_post');
        Route::post('/get', 'all_view_post');
        Route::get('/download/{filekey}', 'all_download_post');
        Route::post('/delete/{filekey}', 'all_delete_post');
        Route::post('/stats', 'all_stats_post');
    });

Route::controller(AffiliateController::class)
    ->prefix('affiliate')
    ->group(function (): void {
        Route::get('/withdrawal', 'withdrawal');
        Route::post('/withdrawal', 'withdrawal_post');
        Route::post('/withdrawal/request', 'withdrawal_request');
        Route::post('/withdrawal/cancel/{id}', 'withdrawal_cancel');
        Route::post('/withdrawal/balance', 'withdrawal_balance');

        Route::get('/statistics', 'statistics');
        Route::post('/statistics', 'statistics_post');
    });

Route::controller(PaymentsController::class)
    ->prefix('payments')
    ->group(function (): void {
        Route::get('/all', 'payments');
        Route::post('/all', 'payments_post');
        Route::post('/get/{id}', 'payments_single_post');
        Route::get('/plan', 'plan');
    });

Route::controller(SettingsController::class)
    ->prefix('settings')
    ->group(function (): void {
        Route::get('/', 'settings');
        Route::post('/', 'settings_post');
    });

Route::controller(ApiController::class)
    ->prefix('api')
    ->group(function (): void {
        Route::get('/', 'api');
    });

Route::controller(LogsController::class)
    ->prefix('logs')
    ->group(function (): void {
        Route::get('/', 'logs');
        Route::post('/', 'logs_post');
    });

Route::controller(UploadController::class)
    ->group(function () {
        Route::get('/upload', 'upload');
        Route::post('/upload', 'upload_post');
        Route::post('/upload-link', 'upload_link');
    });

Route::get('/logout', [AuthController::class, 'logout']);

Route::controller(HelperController::class)
    ->group(function (): void {
        Route::post('/collapse-sidebar', 'collapse_sidebar');
        Route::post('/color-mode', 'theme_mode');
    });