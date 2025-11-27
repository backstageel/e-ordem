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
        Schema::create('payment_integrations', function (Blueprint $table) {
            $table->id();
            $table->string('provider'); // mpesa, emola, bank, etc.
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('config'); // Store configuration as JSON
            $table->string('environment')->default('sandbox'); // sandbox or production
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Create table for payment integration logs
        Schema::create('payment_integration_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_integration_id')->constrained();
            $table->foreignId('payment_id')->nullable()->constrained();
            $table->string('transaction_id')->nullable();
            $table->string('status');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_integration_logs');
        Schema::dropIfExists('payment_integrations');
    }
};
