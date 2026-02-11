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
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->integer('created_by_id');
            $table->ipAddress('created_by_ip');
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by_id');
            $table->ipAddress('updated_by_ip');
            $table->integer('plan');
            $table->integer('gateway');
            $table->integer('duration');
            $table->string('transaction', 250);
            $table->string('info', 250)->nullable();
            $table->integer('revenue');
            $table->tinyInteger('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
