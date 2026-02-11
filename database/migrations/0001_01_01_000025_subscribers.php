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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->ipAddress('created_by_ip');
            $table->timestamp('updated_at')->nullable();
            $table->ipAddress('updated_by_ip');
            $table->string('email', 250);
            $table->string('verification_code', 250)->nullable();
            $table->tinyInteger('verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
