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
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['medical_speciality_id']);
            $table->dropIndex(['medical_speciality_id']);
            $table->dropColumn('medical_speciality_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('medical_speciality_id')->nullable()->after('specialty')->constrained('medical_specialities')->onDelete('set null');
            $table->index('medical_speciality_id');
        });
    }
};
