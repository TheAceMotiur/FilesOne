<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings_storage', function (Blueprint $table) {
            // Track bandwidth usage in bytes
            $table->bigInteger('bandwidth_used')->default(0)->after('default');
            
            // Daily bandwidth limit in bytes (default 700GB for free accounts)
            $table->bigInteger('bandwidth_limit')->default(751619276800)->after('bandwidth_used');
            
            // When the bandwidth counter was last reset
            $table->timestamp('bandwidth_reset_at')->nullable()->after('bandwidth_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings_storage', function (Blueprint $table) {
            $table->dropColumn(['bandwidth_used', 'bandwidth_limit', 'bandwidth_reset_at']);
        });
    }
};
