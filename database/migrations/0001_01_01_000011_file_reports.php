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
        Schema::create('file_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->integer('created_by_id')->nullable();
            $table->ipAddress('created_by_ip');
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by_id')->nullable();
            $table->ipAddress('updated_by_ip');
            $table->integer('file_id');
            $table->integer('file_uploader')->nullable();
            $table->string('reporter', 250);
            $table->text('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_reports');
    }
};
