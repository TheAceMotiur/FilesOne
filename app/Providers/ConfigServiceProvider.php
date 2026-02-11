<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (
            env('INSTALLED') == '%installed%' 
            && !request()->routeIs('install')
        ) {
            $settings = DB::table('settings')->select('name', 'value')->pluck('value', 'name')->toArray();
            $emailSettings = DB::table('email_settings')->select('name', 'value')->pluck('value', 'name')->toArray();
            $storages = DB::table('settings_storage')->select('storage_key', 'value')->pluck('value', 'storage_key')->toArray();
            $amazonS3 = json_decode($storages['s3'], true);
            $google = json_decode($storages['google'], true);
            $cloudflareR2 = json_decode($storages['r2'], true);
            $wasabi = json_decode($storages['wasabi'], true);
            $ftp = json_decode($storages['ftp'], true);
            $language = DB::table('language_settings')->select('name', 'value')->pluck('value', 'name')->toArray();

            config(['recaptchav3.origin' => 'https://www.google.com/recaptcha' ?? NULL]);
            config(['recaptchav3.sitekey' => $settings['recaptcha_site'] ?? NULL]);
            config(['recaptchav3.secret' => $settings['recaptcha_secret'] ?? NULL]);
            config(['recaptchav3.base_language' => $language['base_language'] ?? NULL]);

            config(['filesystems.disks.s3.key' => $amazonS3['access_key_id'] ?? NULL]);
            config(['filesystems.disks.s3.secret' => $amazonS3['secret_access_key'] ?? NULL]);
            config(['filesystems.disks.s3.region' => $amazonS3['default_region'] ?? NULL]);
            config(['filesystems.disks.s3.bucket' => $amazonS3['bucket'] ?? NULL]);
            config(['filesystems.disks.s3.url' => $amazonS3['url'] ?? NULL]);

            config(['filesystems.disks.google.clientId' => $google['client_id'] ?? NULL]);
            config(['filesystems.disks.google.clientSecret' => $google['client_secret'] ?? NULL]);
            config(['filesystems.disks.google.refreshToken' => $google['refresh_token'] ?? NULL]);
            config([
                'filesystems.disks.google.folder' => isset($google['folder']) && $google['folder'] 
                ? $google['folder'] 
                : NULL
            ]);
            
            config(['filesystems.disks.r2.key' => $cloudflareR2['access_key_id'] ?? NULL]);
            config(['filesystems.disks.r2.secret' => $cloudflareR2['secret_access_key'] ?? NULL]);
            config(['filesystems.disks.r2.bucket' => $cloudflareR2['bucket'] ?? NULL]);
            config(['filesystems.disks.r2.endpoint' => $cloudflareR2['endpoint'] ?? NULL]);
            config(['filesystems.disks.r2.url' => $cloudflareR2['url'] ?? NULL]);

            config(['filesystems.disks.wasabi.key' => $wasabi['access_key_id'] ?? NULL]);
            config(['filesystems.disks.wasabi.secret' => $wasabi['secret_access_key'] ?? NULL]);
            config(['filesystems.disks.wasabi.bucket' => $wasabi['bucket'] ?? NULL]);
            config(['filesystems.disks.wasabi.region' => $wasabi['default_region'] ?? NULL]);
            config(['filesystems.disks.wasabi.url' => $wasabi['url'] ?? NULL]);
            config(['filesystems.disks.wasabi.endpoint' => $wasabi['url'] ?? NULL]);

            config(['filesystems.disks.ftp.host' => $ftp['host'] ?? NULL]);
            config(['filesystems.disks.ftp.username' => $ftp['username'] ?? NULL]);
            config(['filesystems.disks.ftp.password' => $ftp['password'] ?? NULL]);
            config([
                'filesystems.disks.ftp.port' => isset($ftp['port']) && $ftp['port'] ? (int) intval($ftp['port']) : 21
            ]);
            config(['filesystems.disks.ftp.root' => $ftp['path'] ?? NULL]);
            config(['filesystems.disks.ftp.url' => $ftp['url'] ?? NULL]);
            
            config(['mail.default' => 'smtp']);
            config(['mail.mailers.smtp.host' => $emailSettings['smtp_host'] ?? NULL]);
            config([
                'mail.mailers.smtp.port' => isset($emailSettings['smtp_port']) && $emailSettings['smtp_port'] 
                    ? $emailSettings['smtp_port'] 
                    : 587
            ]);
            config(['mail.mailers.smtp.encryption' => $emailSettings['smtp_encryption'] ?? NULL]);
            config(['mail.mailers.smtp.username' => $emailSettings['smtp_user'] ?? NULL]);
            config(['mail.mailers.smtp.password' => $emailSettings['smtp_password'] ?? NULL]);
        }
    }
}

