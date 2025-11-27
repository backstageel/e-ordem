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
        Schema::create('document_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_type_id')->constrained('registration_types');
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->boolean('is_required')->default(true);
            $table->boolean('requires_translation')->default(false);
            $table->boolean('requires_validation')->default(true);
            $table->integer('order')->default(0);
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['registration_type_id', 'document_type_id'], 'unique_registration_type_document_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_checklists');
    }
};
