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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('member_number')->unique()->nullable();
            $table->string('registration_number')->unique()->nullable();
            $table->date('registration_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('professional_category')->nullable();
            $table->string('specialty')->nullable();
            $table->string('sub_specialty')->nullable();
            $table->string('workplace')->nullable();
            $table->string('workplace_address')->nullable();
            $table->string('workplace_phone')->nullable();
            $table->string('workplace_email')->nullable();
            $table->string('academic_degree')->nullable();
            $table->string('university')->nullable();
            $table->string('school_faculty')->nullable();
            $table->string('other_degrees')->nullable();
            $table->text('literary_qualifications')->nullable();
            $table->text('professional_qualifications')->nullable();
            $table->string('academic_registration_number')->nullable();
            $table->string('degree_type')->nullable();
            $table->integer('graduation_year')->nullable();
            $table->string('academic_merit')->nullable();
            $table->date('graduation_date')->nullable();
            $table->string('status')->default('active'); // active, inactive, suspended
            $table->string('inactivation_reason')->nullable();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('dues_paid')->default(false);
            $table->date('dues_paid_until')->nullable();
            $table->string('qr_code')->nullable();
            $table->json('status_history')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('previous_license_number')->nullable();
            $table->text('detailed_experience')->nullable();
            $table->string('current_position')->nullable();
            $table->date('work_start_date')->nullable();
            $table->date('work_end_date')->nullable();
            $table->string('service_institution')->nullable();
            $table->string('service_sector')->nullable();
            $table->date('application_date')->nullable();
            $table->string('application_signature')->nullable();
            $table->date('entry_date')->nullable();
            $table->string('entry_category')->nullable();
            $table->string('professional_reference_1_name')->nullable();
            $table->string('professional_reference_1_phone')->nullable();
            $table->string('professional_reference_1_email')->nullable();
            $table->string('professional_reference_2_name')->nullable();
            $table->string('professional_reference_2_phone')->nullable();
            $table->string('professional_reference_2_email')->nullable();
            $table->text('professional_affiliations')->nullable();
            $table->json('languages_spoken')->nullable();
            $table->text('research_interests')->nullable();
            $table->text('publications')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->boolean('data_consent')->default(false);
            $table->boolean('truth_declaration')->default(false);
            $table->timestamp('terms_accepted_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('status');
            $table->index('specialty');
            $table->index('professional_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
