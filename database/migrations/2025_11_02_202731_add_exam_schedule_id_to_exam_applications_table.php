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
        Schema::table('exam_applications', function (Blueprint $table) {
            $table->foreignId('exam_schedule_id')->nullable()->after('exam_id')->constrained('exam_schedules')->onDelete('set null');
            $table->index('exam_schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_applications', function (Blueprint $table) {
            $table->dropForeign(['exam_schedule_id']);
            $table->dropIndex(['exam_schedule_id']);
            $table->dropColumn('exam_schedule_id');
        });
    }
};
