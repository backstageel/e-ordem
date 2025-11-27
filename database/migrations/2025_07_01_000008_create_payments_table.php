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
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('default_amount', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('person_id')->constrained('people');
            $table->foreignId('payment_type_id')->constrained('payment_types');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods');
            $table->string('reference_number')->unique();
            $table->string('unique_reference')->unique()->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('payment_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status'); // pending, completed, failed, refunded
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_path')->nullable();
            $table->json('integration_data')->nullable();
            $table->string('reconciliation_status')->default('pending');
            $table->timestamp('reconciled_at')->nullable();
            $table->morphs('payable'); // For polymorphic relationship with registrations, exams, etc.
            $table->foreignId('recorded_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('status');
            $table->index('payment_type_id');
            $table->index('payment_method_id');
            $table->index('payment_date');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('payment_types');
    }
};
