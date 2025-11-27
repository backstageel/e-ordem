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
        Schema::create('process_histories', function (Blueprint $table) {
            $table->id();
            $table->morphs('processable'); // For polymorphic relationship with registrations, exams, etc.
            $table->foreignId('workflow_state_id')->constrained('workflow_states');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // created, updated, approved, rejected, etc.
            $table->text('description')->nullable();
            $table->json('data_before')->nullable(); // Data before the change
            $table->json('data_after')->nullable(); // Data after the change
            $table->text('notes')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['processable_type', 'processable_id'], 'processable_index');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_histories');
    }
};
