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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_application_id')->constrained('exam_applications');
            $table->decimal('grade', 4, 1)->nullable();
            $table->enum('status', ['presente', 'ausente', 'eliminado'])->default('presente');
            $table->enum('decision', ['aprovado', 'reprovado', 'recurso'])->nullable();
            $table->enum('decision_type', [
                'aprovacao_automatica',
                'aprovacao_manual',
                'reprovacao_automatica',
                'reprovacao_manual',
                'recurso',
            ])->nullable();
            $table->text('notes')->nullable();
            $table->string('certificate_path')->nullable();
            $table->boolean('notification_sent')->default(false);
            $table->foreignId('evaluated_by')->nullable()->constrained('users');
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('decision');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
