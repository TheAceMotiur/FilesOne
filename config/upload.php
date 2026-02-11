<?php

return [

    /*
    |--------------------------------------------------------------------------
    | What file types can be uploaded?
    |--------------------------------------------------------------------------
    */
    'UPLOADABLE_TYPES' => 'zip,apng,avif,gif,ico,jpe,jpeg,jpg,png,webp,heic,mp4,avi,wmv,webm',

    /*
    |--------------------------------------------------------------------------
    | What is the size limit for a single file to upload? (kilobyte | 1MB=1024KB)
    |--------------------------------------------------------------------------
    */
    'MAX_FILE_SIZE' => 5242880,

    /*
    |--------------------------------------------------------------------------
    | What is the maximum number of files that can be uploaded at once?
    | Note: Frontend validation only.
    |--------------------------------------------------------------------------
    */
    'MAX_FILE_COUNT' => 10,

    /*
    |--------------------------------------------------------------------------
    | What is the total size of the server or storage? (kilobyte | 1MB=1024KB)
    |--------------------------------------------------------------------------
    */
    'SERVER_QUOTA' => 5242880,
];
