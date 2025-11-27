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
        Schema::create('exam_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('exam_type', ['certificacao', 'especialidade', 'revalidacao', 'recertificacao']);
            $table->string('specialty');
            $table->string('other_specialty')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_location')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->string('recommendation_letter_path')->nullable();
            $table->string('additional_documents_path')->nullable();
            $table->text('experience_summary')->nullable();
            $table->enum('experience_years', ['menos_1', '1_3', '3_5', '5_10', 'mais_10'])->nullable();
            $table->string('current_institution')->nullable();
            $table->text('special_needs')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->enum('status', ['draft', 'submitted', 'in_review', 'approved', 'rejected', 'documents_pending'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->boolean('is_present')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('status');
            $table->index('exam_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_applications');
    }
};
