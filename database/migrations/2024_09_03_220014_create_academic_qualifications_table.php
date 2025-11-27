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
        Schema::create('academic_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->foreignId('institution_id')->nullable()->constrained('academic_institutions')->onDelete('set null');
            $table->string('qualification_type'); // degree, diploma, certificate, etc.
            $table->string('field_of_study');
            $table->string('institution_name')->nullable(); // Fallback if institution not in database
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->string('grade')->nullable();
            $table->string('gpa')->nullable();
            $table->string('certificate_number')->nullable();
            $table->string('certificate_path')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('person_id');
            $table->index('institution_id');
            $table->index('qualification_type');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_qualifications');
    }
};
