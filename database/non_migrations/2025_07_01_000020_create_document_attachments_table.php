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
        Schema::create('document_attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('attachable'); // Can be registration, member, etc.
            $table->string('document_name');
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->string('file_hash')->nullable();
            $table->date('attachment_date');
            $table->text('description')->nullable();
            $table->boolean('is_authenticated')->default(false);
            $table->string('authenticated_by')->nullable();
            $table->date('authentication_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_attachments');
    }
};
