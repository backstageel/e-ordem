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
        Schema::create('cancellation_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('cancelled_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cancellation_reason_id')->constrained('cancellation_reasons');
            $table->morphs('cancellable'); // For polymorphic relationship with registrations, exams, etc.
            $table->text('additional_notes')->nullable();
            $table->date('cancellation_date');
            $table->foreignId('cancelled_by')->constrained('users');
            $table->boolean('is_notified')->default(false);
            $table->date('notification_date')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('cancellation_date');
            $table->index('cancellation_reason_id');
        });

        Schema::create('archived_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->morphs('archivable'); // For polymorphic relationship with registrations, exams, etc.
            $table->string('reason'); // inactive, completed, expired
            $table->text('additional_notes')->nullable();
            $table->date('archive_date');
            $table->foreignId('archived_by')->nullable()->constrained('users');
            $table->boolean('is_automatic')->default(false);
            $table->boolean('is_notified')->default(false);
            $table->date('notification_date')->nullable();
            $table->timestamps();

            // Add indexes for better performance
            $table->index('archive_date');
            $table->index('reason');
        });

        // Seed cancellation reasons
        DB::table('cancellation_reasons')->insert([
            [
                'name' => 'Incomplete Documentation',
                'description' => 'Process cancelled due to incomplete documentation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'False Information',
                'description' => 'Process cancelled due to false information provided',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Duplicate Application',
                'description' => 'Process cancelled due to duplicate application',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Member Request',
                'description' => 'Process cancelled at member\'s request',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Payment Issue',
                'description' => 'Process cancelled due to payment issues',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ethical Violation',
                'description' => 'Process cancelled due to ethical violations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Other',
                'description' => 'Process cancelled for other reasons',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archived_processes');
        Schema::dropIfExists('cancelled_processes');
        Schema::dropIfExists('cancellation_reasons');
    }
};
