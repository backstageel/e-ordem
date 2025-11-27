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
        Schema::create('exam_appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('application_id')->constrained('exam_applications')->onDelete('cascade');
            $table->foreignId('result_id')->nullable()->constrained('exam_results')->onDelete('set null');
            $table->enum('appeal_type', ['correction', 'other'])->default('correction');
            $table->timestamp('submitted_at');
            $table->enum('submitted_via', ['email', 'physical', 'online'])->default('online');
            $table->date('deadline_date')->comment('10 business days after results publication');
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('jury_proposed_by')->nullable()->constrained('users')->comment('Proposed by Revision Commission');
            $table->foreignId('jury_approved_by')->nullable()->constrained('users')->comment('Approved by Bastonário');
            $table->enum('decision', ['approved', 'rejected', 'pending'])->default('pending');
            $table->text('decision_notes')->nullable();
            $table->boolean('is_final')->default(false)->comment('Final decision is unappealable');
            $table->boolean('is_appealable')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index('exam_id');
            $table->index('application_id');
            $table->index('appeal_type');
            $table->index('decision');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_appeals');
    }
};
