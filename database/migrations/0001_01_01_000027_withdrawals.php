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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->nullable();
            $table->integer('created_by_id');
            $table->ipAddress('created_by_ip');
            $table->timestamp('updated_at')->nullable();
            $table->integer('updated_by_id');
            $table->ipAddress('updated_by_ip');
            $table->text('details');
            $table->string('amount', 250);
            $table->integer('payment_method');
            $table->tinyInteger('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
