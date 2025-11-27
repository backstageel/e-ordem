<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('residency_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('specialty');
            $table->integer('duration_months');
            $table->decimal('fee', 10, 2)->default(0);
            $table->integer('max_participants')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('coordinator_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('residency_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code')->nullable();
            $table->foreignId('country_id')->constrained('countries');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('residency_program_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residency_program_id')->constrained()->onDelete('cascade');
            $table->foreignId('residency_location_id')->constrained()->onDelete('cascade');
            $table->integer('available_slots')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('residency_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('residency_program_id')->constrained()->onDelete('cascade');
            $table->foreignId('residency_location_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status'); // pending, approved, rejected, in_progress, completed, cancelled
            $table->date('application_date');
            $table->date('approval_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->boolean('is_paid')->default(false);
            $table->string('payment_reference')->nullable();
            $table->date('payment_date')->nullable();
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('residency_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('residency_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users');
            $table->date('evaluation_date');
            $table->string('period'); // e.g., "Month 1", "Quarter 1"
            $table->decimal('score', 5, 2)->nullable();
            $table->string('grade')->nullable();
            $table->text('comments')->nullable();
            $table->text('recommendations')->nullable();
            $table->boolean('is_satisfactory')->default(true);
            $table->timestamps();
        });

        // Seed some sample residency programs
        /*DB::table('residency_programs')->insert([
            [
                'name' => 'Internal Medicine Residency',
                'description' => 'Comprehensive training in internal medicine',
                'specialty' => 'Internal Medicine',
                'duration_months' => 36,
                'fee' => 5000.00,
                'max_participants' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pediatrics Residency',
                'description' => 'Comprehensive training in pediatrics',
                'specialty' => 'Pediatrics',
                'duration_months' => 36,
                'fee' => 5000.00,
                'max_participants' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surgery Residency',
                'description' => 'Comprehensive training in general surgery',
                'specialty' => 'Surgery',
                'duration_months' => 60,
                'fee' => 6000.00,
                'max_participants' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Obstetrics and Gynecology Residency',
                'description' => 'Comprehensive training in obstetrics and gynecology',
                'specialty' => 'Obstetrics and Gynecology',
                'duration_months' => 48,
                'fee' => 5500.00,
                'max_participants' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Get Mozambique country ID
        $mozambiqueId = DB::table('countries')->where('name', 'Mozambique')->value('id');

        // If Mozambique doesn't exist, use a default ID of 1
        if (!$mozambiqueId) {
            $mozambiqueId = 1;
        }

        // Seed some sample residency locations
        DB::table('residency_locations')->insert([
            [
                'name' => 'Central Hospital',
                'description' => 'Main teaching hospital',
                'address' => '123 Main Street',
                'city' => 'Maputo',
                'province' => 'Maputo',
                'country_id' => $mozambiqueId,
                'phone_number' => '+258 21 123456',
                'email' => 'info@centralhospital.co.mz',
                'capacity' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Provincial Hospital',
                'description' => 'Provincial teaching hospital',
                'address' => '456 Secondary Street',
                'city' => 'Beira',
                'province' => 'Sofala',
                'country_id' => $mozambiqueId,
                'phone_number' => '+258 23 123456',
                'email' => 'info@provincialhospital.co.mz',
                'capacity' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residency_evaluations');
        Schema::dropIfExists('residency_applications');
        Schema::dropIfExists('residency_program_locations');
        Schema::dropIfExists('residency_locations');
        Schema::dropIfExists('residency_programs');
    }
};
