<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add foreign keys to tables that depend on members table
     */
    public function up(): void
    {
        // Documents table
        if (Schema::hasTable('documents') && Schema::hasColumn('documents', 'member_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->foreign('member_id')
                    ->references('id')
                    ->on('members')
                    ->onDelete('cascade');
            });
        }

        // Registrations table
        if (Schema::hasTable('registrations') && Schema::hasColumn('registrations', 'member_id')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->foreign('member_id')
                    ->references('id')
                    ->on('members')
                    ->onDelete('set null');
            });
        }

        // Payments table
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'member_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreign('member_id')
                    ->references('id')
                    ->on('members')
                    ->onDelete('cascade');
            });
        }

        // Cards table
        if (Schema::hasTable('cards') && Schema::hasColumn('cards', 'member_id')) {
            Schema::table('cards', function (Blueprint $table) {
                $table->foreign('member_id')
                    ->references('id')
                    ->on('members')
                    ->onDelete('cascade');
            });
        }

        // Residency applications table
        if (Schema::hasTable('residency_applications') && Schema::hasColumn('residency_applications', 'member_id')) {
            Schema::table('residency_applications', function (Blueprint $table) {
                $table->foreign('member_id')
                    ->references('id')
                    ->on('members')
                    ->onDelete('cascade');
            });
        }

        // Specializations table
        if (Schema::hasTable('specializations') && Schema::hasColumn('specializations', 'member_id')) {
            Schema::table('specializations', function (Blueprint $table) {
                $table->foreign('member_id')
                    ->references('id')
                    ->on('members')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['documents', 'registrations', 'payments', 'cards', 'residency_applications', 'specializations'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'member_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    try {
                        $table->dropForeign([$tableName . '_member_id_foreign']);
                    } catch (\Exception $e) {
                        // Foreign key might not exist, ignore
                    }
                });
            }
        }
    }
};
