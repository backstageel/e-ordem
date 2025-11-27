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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->constrained('people')->onDelete('cascade');
            $table->unsignedBigInteger('member_id')->nullable(); // Foreign key will be added later
            $table->unsignedBigInteger('registration_id')->nullable(); // Foreign key will be added later
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->string('file_hash')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->string('digital_signature')->nullable();
            $table->string('status'); // pending, validated, rejected
            $table->date('submission_date');
            $table->date('validation_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users');
            $table->boolean('has_translation')->default(false);
            $table->string('translation_file_path')->nullable();
            $table->string('sworn_translator')->nullable();
            $table->json('checklist_data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('status');
            $table->index('document_type_id');
            $table->index('submission_date');
            $table->index('person_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
