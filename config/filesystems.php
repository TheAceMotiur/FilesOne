<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'lang' => [
            'driver' => 'local',
            'root' => lang_path(),
            'visibility' => 'public',
            'throw' => false,
        ],

        'default' => [
            'driver' => 'local',
            'root' => storage_path('app/uploads'),
            'url' => env('APP_URL').'public/get',
            'visibility' => 'private',
            'throw' => false,
        ],

        'temp' => [
            'driver' => 'local',
            'root' => storage_path('app/temp'),
            'url' => env('APP_URL').'public/get',
            'visibility' => 'private',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'visibility' => 'public',
            'throw' => true,
        ],

        'google' => [
            'driver' => 'google',
            'clientId' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refreshToken' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'folder' => env('GOOGLE_DRIVE_FOLDER'),
            'visibility' => 'public',
            'throw' => true,
        ],

        // Additional Google Drive accounts for fallback
        'google_2' => [
            'driver' => 'google',
            'clientId' => env('GOOGLE_DRIVE_2_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_DRIVE_2_CLIENT_SECRET'),
            'refreshToken' => env('GOOGLE_DRIVE_2_REFRESH_TOKEN'),
            'folder' => env('GOOGLE_DRIVE_2_FOLDER'),
            'visibility' => 'public',
            'throw' => true,
        ],

        'google_3' => [
            'driver' => 'google',
            'clientId' => env('GOOGLE_DRIVE_3_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_DRIVE_3_CLIENT_SECRET'),
            'refreshToken' => env('GOOGLE_DRIVE_3_REFRESH_TOKEN'),
            'folder' => env('GOOGLE_DRIVE_3_FOLDER'),
            'visibility' => 'public',
            'throw' => true,
        ],

        'google_4' => [
            'driver' => 'google',
            'clientId' => env('GOOGLE_DRIVE_4_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_DRIVE_4_CLIENT_SECRET'),
            'refreshToken' => env('GOOGLE_DRIVE_4_REFRESH_TOKEN'),
            'folder' => env('GOOGLE_DRIVE_4_FOLDER'),
            'visibility' => 'public',
            'throw' => true,
        ],

        'google_5' => [
            'driver' => 'google',
            'clientId' => env('GOOGLE_DRIVE_5_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_DRIVE_5_CLIENT_SECRET'),
            'refreshToken' => env('GOOGLE_DRIVE_5_REFRESH_TOKEN'),
            'folder' => env('GOOGLE_DRIVE_5_FOLDER'),
            'visibility' => 'public',
            'throw' => true,
        ],
        
        'r2' => [
            'driver' => 's3',
            'region' => 'auto',
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'visibility' => 'public',
            'throw' => true,
        ],

        'wasabi' => [
            'driver' => 's3',
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'visibility' => 'public',
            'throw' => true,
        ],

        'ftp' => [
            'driver' => 'ftp',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
