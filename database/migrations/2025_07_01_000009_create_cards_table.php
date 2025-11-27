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
        Schema::create('card_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('color_code')->nullable();
            $table->integer('validity_period_days')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('card_type_id')->constrained('card_types');
            $table->string('card_number')->unique();
            $table->string('status'); // pending, issued, active, expired, revoked, lost
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('qr_code')->nullable();
            $table->string('physical_card_path')->nullable(); // Path to physical card image
            $table->string('digital_card_path')->nullable(); // Path to digital card image
            $table->boolean('is_physical')->default(true);
            $table->boolean('is_digital')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users');
            $table->boolean('is_paid')->default(false);
            $table->string('payment_reference')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->string('qr_code_path')->nullable();
            $table->text('reissue_reason')->nullable();
            $table->integer('reissue_count')->default(0);
            $table->json('status_history')->nullable();
            $table->enum('card_type', ['professional_card', 'badge', 'digital_wallet'])->nullable();
            $table->enum('issue_reason', ['first_issue', 'second_issue', 'renewal', 'update', 'damaged'])->nullable();
            $table->enum('urgency', ['normal', 'urgent', 'express'])->default('normal')->nullable();
            $table->enum('delivery_method', ['pickup', 'mail', 'courier'])->default('pickup')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('observations')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('expected_delivery_date')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('status');
            $table->index('card_type_id');
            $table->index('issue_date');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
        Schema::dropIfExists('card_types');
    }
};
