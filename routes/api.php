<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Rest\UploadController;

Route::controller(UploadController::class)
    ->group(function () {
        Route::post('/upload', 'upload')
            ->middleware('throttle:restUpload');
        Route::post('/upload-link', 'upload_link')
            ->middleware('throttle:restUploadUrl');
    });