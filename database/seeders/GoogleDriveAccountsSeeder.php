<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoogleDriveAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates default Google Drive storage accounts if they don't exist.
     * Run this after initial installation or when migrating to multiple Google Drive support.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        
        // Check if the primary Google Drive account exists
        $existingGoogle = DB::table('settings_storage')
            ->where('storage_key', 'google')
            ->first();
        
        if (!$existingGoogle) {
            // Create the primary Google Drive account
            DB::table('settings_storage')->insert([
                'name' => 'Google Drive',
                'value' => json_encode([
                    'client_id' => '',
                    'client_secret' => '',
                    'refresh_token' => '',
                    'folder' => '',
                ]),
                'storage_key' => 'google',
                'default' => 0, // Set to 1 if you want it as default
                'created_at' => $now,
                'updated_at' => $now,
                'created_by_id' => null,
                'created_by_ip' => null,
                'updated_by_id' => null,
                'updated_by_ip' => null,
            ]);
            
            $this->command->info('Primary Google Drive account created.');
        } else {
            $this->command->info('Primary Google Drive account already exists.');
        }
        
        // Optionally create additional Google Drive accounts
        // Uncomment the following lines to create more accounts during seeding
        
        /*
        $googleAccounts = [
            [
                'name' => 'Google Drive 2',
                'storage_key' => 'google_2',
            ],
            [
                'name' => 'Google Drive 3',
                'storage_key' => 'google_3',
            ],
            [
                'name' => 'Google Drive 4',
                'storage_key' => 'google_4',
            ],
            [
                'name' => 'Google Drive 5',
                'storage_key' => 'google_5',
            ],
        ];
        
        foreach ($googleAccounts as $account) {
            $exists = DB::table('settings_storage')
                ->where('storage_key', $account['storage_key'])
                ->exists();
            
            if (!$exists) {
                DB::table('settings_storage')->insert([
                    'name' => $account['name'],
                    'value' => json_encode([
                        'client_id' => '',
                        'client_secret' => '',
                        'refresh_token' => '',
                        'folder' => '',
                    ]),
                    'storage_key' => $account['storage_key'],
                    'default' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'created_by_id' => null,
                    'created_by_ip' => null,
                    'updated_by_id' => null,
                    'updated_by_ip' => null,
                ]);
                
                $this->command->info("Created {$account['name']} account.");
            }
        }
        */
        
        $this->command->info('Google Drive accounts seeding completed.');
    }
}
