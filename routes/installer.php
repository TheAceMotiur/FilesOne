<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Installer\InstallerController;

/*
|--------------------------------------------------------------------------
| Installer Routes
|--------------------------------------------------------------------------
*/

Route::controller(InstallerController::class)
    ->group(function() {
        Route::get('/install', 'index')
            ->name('installer');
        Route::post('/install/{type}', 'installer')
            ->whereIn(
                'type', 
                ['requirements', 'database', 'settings', 'finish']
            );
});