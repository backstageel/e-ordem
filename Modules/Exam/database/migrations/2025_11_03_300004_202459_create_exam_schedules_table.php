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
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->enum('period_type', ['ordinary', 'extraordinary'])->default('ordinary');
            $table->string('period_name')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location');
            $table->text('address')->nullable();
            $table->integer('capacity');
            $table->integer('available_slots');
            $table->integer('minimum_candidates_required')->default(100)->comment('Minimum candidates for extraordinary periods');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->foreignId('supervisor_id')->nullable()->constrained('users');
            $table->boolean('attendance_sheet_required')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('exam_id');
            $table->index('date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_schedules');
    }
};
