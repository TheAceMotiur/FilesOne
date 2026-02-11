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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->integer('created_by_id');
            $table->ipAddress('created_by_ip');
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by_id');
            $table->ipAddress('updated_by_ip');
            $table->string('name', 250);
            $table->string('url', 250);
            $table->text('settings')->nullable();
            $table->text('content');
            $table->text('seo');
            $table->string('page_key', 250);
            $table->tinyInteger('status');
            $table->tinyInteger('custom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
