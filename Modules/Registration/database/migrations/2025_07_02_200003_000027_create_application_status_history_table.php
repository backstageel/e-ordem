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
        Schema::create('application_status_history', function (Blueprint $table) {
            $table->id();
            $table->morphs('application'); // Can be registration, exam_application, etc.
            $table->foreignId('status_id')->constrained('application_statuses');
            $table->foreignId('changed_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->timestamp('changed_at');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_status_history');
    }
};
