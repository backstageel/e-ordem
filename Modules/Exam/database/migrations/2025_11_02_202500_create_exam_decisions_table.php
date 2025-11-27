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
        Schema::create('exam_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('application_id')->constrained('exam_applications')->onDelete('cascade');
            $table->foreignId('result_id')->nullable()->constrained('exam_results')->onDelete('set null');
            $table->enum('decision_type', ['approved', 'rejected', 'pending'])->default('pending');
            $table->date('decision_date');
            $table->foreignId('signed_by_president')->nullable()->constrained('users')->comment('President of Certification Council');
            $table->foreignId('homologated_by_bastonario')->nullable()->constrained('users')->comment('Bastonário of OrMM');
            $table->text('notes')->nullable();
            $table->boolean('published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->boolean('sent_to_colleges')->default(false);
            $table->boolean('sent_to_directors')->default(false);
            $table->boolean('sent_to_dnfps')->default(false)->comment('Sent to Direção Nacional de Formação de Profissionais de Saúde');
            $table->timestamps();
            $table->softDeletes();

            $table->index('exam_id');
            $table->index('application_id');
            $table->index('decision_type');
            $table->index('published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_decisions');
    }
};
