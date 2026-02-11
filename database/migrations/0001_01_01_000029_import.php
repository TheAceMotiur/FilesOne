<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sqlFile = database_path('migrations/data.sql');
        if (file_exists($sqlFile)) {
            sleep(1);
            DB::unprepared(file_get_contents($sqlFile));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
