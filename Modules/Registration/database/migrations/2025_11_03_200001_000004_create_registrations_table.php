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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_type_id')->constrained('registration_types');
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->unsignedBigInteger('member_id')->nullable(); // Foreign key will be added later

            // Campo discriminador de tipo
            $table->string('type')->nullable()->comment('certification, provisional, effective');

            $table->string('process_number')->unique()->nullable();
            $table->string('qr_code_path')->nullable()->comment('Caminho do QR code gerado');
            $table->string('registration_number')->unique();
            $table->string('status'); // draft, submitted, under_review, documents_pending, payment_pending, validated, approved, rejected, archived, expired
            $table->date('submission_date');
            $table->string('priority_level')->default('normal');
            $table->date('approval_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('initial_review_date')->nullable();
            $table->date('final_review_date')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->string('received_by')->nullable();
            $table->boolean('documents_checked')->default(false);
            $table->text('coordinator_dispatch')->nullable();
            $table->string('coordinator_signature')->nullable();
            $table->string('process_type')->nullable();
            $table->boolean('requires_equivalence_certificate')->default(false);
            $table->boolean('requires_criminal_record')->default(true);
            $table->boolean('requires_photos')->default(true);
            $table->integer('photo_count')->default(3);
            $table->text('special_requirements')->nullable();
            $table->json('additional_documents_required')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('payment_reference')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->boolean('documents_validated')->default(false);
            $table->boolean('is_renewal')->default(false);
            $table->foreignId('previous_registration_id')->nullable()->constrained('registrations');
            $table->json('workflow_history')->nullable();
            $table->boolean('notifications_sent')->default(false);
            $table->json('notification_history')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Add indexes for better performance
            $table->index('status');
            $table->index('submission_date');
            $table->index('registration_type_id');

            // Registration-specific fields (not personal data)
            $table->string('professional_category')->nullable();
            $table->string('sub_specialty')->nullable();
            $table->string('workplace')->nullable();
            $table->string('workplace_address')->nullable();
            $table->string('workplace_phone')->nullable();
            $table->string('workplace_email')->nullable();
            $table->string('academic_degree')->nullable();

            // Provisional registration specific fields
            $table->string('inviting_entity')->nullable();
            $table->string('duration_type')->nullable();
            $table->string('activity_location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('activity_description')->nullable();

            // Campos específicos para Certification (Pré-inscrição para Certificação)
            $table->tinyInteger('category')->nullable()->comment('1=Moçambicanos formados em MZ, 2=Moçambicanos formados no estrangeiro, 3=Estrangeiros formados em MZ');
            $table->unsignedBigInteger('exam_application_id')->nullable();
            $table->unsignedBigInteger('exam_result_id')->nullable();
            $table->decimal('exam_grade', 4, 1)->nullable()->comment('Nota do exame');

            // Campos específicos para Provisional (Inscrições Provisórias)
            $table->tinyInteger('subtype')->nullable()->comment('1-12 conforme subtipo de inscrição provisória');
            $table->integer('duration_days')->nullable()->comment('Duração em dias (3, 10, 12, 24 meses)');
            $table->unsignedBigInteger('supervisor_id')->nullable()->comment('Médico moçambicano supervisor');

            // Campos específicos para Effective (Inscrições Efetivas)
            $table->string('grade')->nullable()->comment('Grau: A=Especialistas, B=Clínica Geral, C=Dentistas');
            $table->string('grade_subcategory')->nullable()->comment('A1-A3, B1-B4, C1-C4');
            $table->integer('years_of_experience')->nullable()->comment('Anos de experiência para classificação');

            // Índices para performance
            $table->index('type');
            $table->index('category');
            $table->index('subtype');
            $table->index('grade');
            $table->index('exam_application_id');
            $table->index('exam_result_id');
            $table->index('supervisor_id');
        });

        // Adicionar foreign keys se as tabelas existirem
        if (Schema::hasTable('exam_applications')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->foreign('exam_application_id')->references('id')->on('exam_applications')->onDelete('set null');
            });
        }

        if (Schema::hasTable('exam_results')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->foreign('exam_result_id')->references('id')->on('exam_results')->onDelete('set null');
            });
        }

        if (Schema::hasTable('members')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->foreign('supervisor_id')->references('id')->on('members')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
