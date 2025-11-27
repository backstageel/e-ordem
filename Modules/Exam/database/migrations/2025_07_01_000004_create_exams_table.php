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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['teorico', 'pratico', 'oral', 'misto']);
            $table->enum('level', ['basico', 'intermediario', 'avancado'])->nullable();
            $table->string('specialty');
            $table->text('description')->nullable();
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration')->comment('Duration in minutes');
            $table->string('location');
            $table->text('address')->nullable();
            $table->integer('capacity');
            $table->decimal('minimum_grade', 4, 1)->default(10.0);
            $table->integer('questions_count')->nullable();
            $table->integer('time_limit')->nullable()->comment('Time limit in minutes');
            $table->integer('attempts_allowed')->default(1);
            $table->boolean('allow_consultation')->default(false);
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('immediate_result')->default(false);
            $table->foreignId('primary_evaluator_id')->nullable()->constrained('users');
            $table->foreignId('secondary_evaluator_id')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->json('admitted_list')->nullable();
            $table->json('excluded_list')->nullable();
            $table->boolean('payment_required')->default(true);
            $table->json('notification_settings')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'in_progress', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_applications');
        Schema::dropIfExists('exams');
    }
};
