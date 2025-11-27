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
        Schema::create('payment_references', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('type'); // registration, exam, residence, etc.
            $table->morphs('referenceable'); // For polymorphic relationship
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('MZN');
            $table->date('due_date');
            $table->string('status')->default('pending'); // pending, paid, expired, cancelled
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_references');
    }
};
