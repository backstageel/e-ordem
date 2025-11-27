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
        Schema::create('backup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // full, incremental, differential
            $table->string('status'); // success, failed, in_progress
            $table->string('backup_path')->nullable();
            $table->bigInteger('file_size')->nullable(); // Size in bytes
            $table->string('checksum')->nullable(); // File checksum for integrity
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable(); // Additional backup metadata
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_logs');
    }
};
