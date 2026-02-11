<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration ensures the settings_storage table structure supports
     * multiple Google Drive accounts and updates existing Google Drive entry if needed.
     */
    public function up(): void
    {
        // Check if the table exists
        if (!Schema::hasTable('settings_storage')) {
            // Create the table if it doesn't exist
            Schema::create('settings_storage', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->unsignedBigInteger('created_by_id')->nullable();
                $table->string('created_by_ip', 45)->nullable();
                $table->unsignedBigInteger('updated_by_id')->nullable();
                $table->string('updated_by_ip', 45)->nullable();
                $table->string('name', 100);
                $table->text('value')->nullable();
                $table->string('storage_key', 50)->unique();
                $table->boolean('default')->default(0);
            });
        }
        
        // Update existing Google Drive account if it exists with old ID format
        DB::table('settings_storage')
            ->where('id', 6)
            ->where('storage_key', '!=', 'google')
            ->update(['storage_key' => 'google']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration doesn't need a down method as it just ensures structure
        // Dropping the table would affect other storage providers
    }
};
