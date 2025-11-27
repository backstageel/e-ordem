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
        Schema::create('medical_speciality_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('medical_speciality_id')->constrained('medical_specialities')->onDelete('cascade');
            $table->boolean('is_primary')->default(false)->comment('Indica se esta é a especialidade principal do membro');
            $table->timestamps();

            // Unique constraint para evitar duplicatas
            $table->unique(['member_id', 'medical_speciality_id']);

            // Indexes
            $table->index('member_id');
            $table->index('medical_speciality_id');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_speciality_member');
    }
};
