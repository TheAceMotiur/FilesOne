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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->ipAddress('created_by_ip');
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by_id')->nullable();
            $table->ipAddress('updated_by_ip');
            $table->string('name', 250);
            $table->string('email', 250)->unique();
            $table->tinyInteger('type')->default(1);
            $table->string('photo', 250)->nullable();
            $table->string('password', 250);
            $table->tinyInteger('verified');
            $table->string('verification_token', 250)->nullable();
            $table->string('reset_token', 250)->nullable();
            $table->string('remember_token', 250)->nullable();
            $table->string('api_token', 250)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
