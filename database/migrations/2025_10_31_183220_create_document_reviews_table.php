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
        Schema::create('document_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('reviewed_by')->constrained('users')->onDelete('cascade');
            $table->string('review_status'); // pending, approved, rejected, requires_changes
            $table->text('review_notes')->nullable();
            $table->text('feedback')->nullable();
            $table->json('validation_results')->nullable(); // Store automated validation results
            $table->timestamp('reviewed_at')->nullable();
            $table->integer('review_order')->default(0); // Order in which reviews were conducted
            $table->foreignId('previous_review_id')->nullable()->constrained('document_reviews')->onDelete('set null');
            $table->timestamps();

            $table->index(['document_id', 'reviewed_by']);
            $table->index('review_status');
            $table->index('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_reviews');
    }
};
