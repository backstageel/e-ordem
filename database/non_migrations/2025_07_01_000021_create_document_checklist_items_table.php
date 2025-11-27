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
        Schema::create('document_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->string('document_name');
            $table->boolean('is_required')->default(true);
            $table->boolean('is_submitted')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->text('notes')->nullable();
            $table->string('file_path')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('verification_date')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_checklist_items');
    }
};
