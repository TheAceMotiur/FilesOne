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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->integer('created_by_id')->nullable();
            $table->ipAddress('created_by_ip');
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by_id')->nullable();
            $table->ipAddress('updated_by_ip');
            $table->string('filename', 250);
            $table->integer('filesize');
            $table->string('filetype', 250);
            $table->string('disk', 250);
            $table->string('short_key', 250);
            $table->string('autoremove', 250)->nullable();
            $table->string('password', 250)->nullable();
            $table->integer('pageview')->nullable();
            $table->integer('unique_pageview')->nullable();
            $table->integer('download')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
