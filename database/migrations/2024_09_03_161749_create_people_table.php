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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('civility')->nullable(); // Mr, Mrs, etc.
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->foreignId('gender_id')->nullable()->constrained('genders')->onDelete('set null');
            $table->foreignId('marital_status_id')->nullable()->constrained('civil_states')->onDelete('set null');
            $table->foreignId('birth_country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->foreignId('birth_province_id')->nullable()->constrained('provinces')->onDelete('set null');
            $table->foreignId('birth_district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->date('birth_date')->nullable();
            $table->foreignId('identity_document_id')->nullable()->constrained('identity_documents')->onDelete('set null');
            $table->string('identity_document_number')->nullable()->unique(); // Número do documento
            $table->foreignId('nationality_id')->nullable()->constrained('countries')->onDelete('set null'); // FK para países
            $table->date('identity_document_issue_date')->nullable(); // Data de emissão
            $table->string('identity_document_issue_place')->nullable(); // Local de emissão
            $table->date('identity_document_expiry_date')->nullable(); // Data de expiração
            $table->boolean('has_disability')->default(false);
            $table->string('disability_description')->nullable();
            $table->string('phone')->nullable()->unique(); // Telefone principal
            $table->string('mobile')->nullable(); // Telefone móvel
            $table->string('email')->nullable()->unique();
            $table->string('fax')->nullable();
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('living_address')->nullable(); // Endereço de residência
            $table->foreignId('living_country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->foreignId('living_province_id')->nullable()->constrained('provinces')->onDelete('set null');
            $table->foreignId('living_district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->foreignId('living_neighborhood_id')->nullable()->constrained('neighborhoods')->onDelete('set null');
            $table->string('profile_picture_url')->nullable();
            $table->string('tax_number')->nullable(); // NUIT
            $table->text('notes')->nullable();

            // Academic data - Licenciatura (Ensino Superior)
            $table->string('degree_type')->nullable()->comment('Medicina Geral ou Medicina Dentária');
            $table->string('university')->nullable();
            $table->integer('university_start_year')->nullable();
            $table->integer('university_end_year')->nullable();
            $table->foreignId('university_country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->string('university_city_district')->nullable();
            $table->decimal('university_final_grade', 4, 2)->nullable();

            // Academic data - Ensino Médio
            $table->string('high_school_institution')->nullable();
            $table->foreignId('high_school_country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->string('high_school_city_district')->nullable();
            $table->integer('high_school_completion_year')->nullable();
            $table->decimal('high_school_final_grade', 4, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
