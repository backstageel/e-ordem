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
        Schema::create('certification_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->unsignedBigInteger('exam_application_id')->nullable();
            $table->unsignedBigInteger('exam_result_id')->nullable();
            $table->tinyInteger('current_step')->default(1)->comment('1-9 conforme etapas do Edital OrMM 2025');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->json('step_data')->nullable()->comment('Dados específicos de cada etapa');
            $table->json('decisions')->nullable()->comment('Decisões e pareceres por etapa');
            $table->json('history')->nullable()->comment('Histórico completo de transições');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('registration_id');
            $table->index('current_step');
            $table->index('assigned_to');
            $table->index('exam_application_id');
            $table->index('exam_result_id');
        });

        // Adicionar foreign keys se as tabelas existirem
        if (Schema::hasTable('exam_applications')) {
            Schema::table('certification_workflows', function (Blueprint $table) {
                $table->foreign('exam_application_id')->references('id')->on('exam_applications')->onDelete('set null');
            });
        }

        if (Schema::hasTable('exam_results')) {
            Schema::table('certification_workflows', function (Blueprint $table) {
                $table->foreign('exam_result_id')->references('id')->on('exam_results')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_workflows');
    }
};
