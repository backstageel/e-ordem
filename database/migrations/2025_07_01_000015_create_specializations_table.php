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
        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->string('specialty_name');
            $table->string('subspecialty')->nullable();
            $table->string('institution_name');
            $table->string('institution_country')->nullable();
            $table->date('start_date');
            $table->date('completion_date');
            $table->string('certificate_number')->nullable();
            $table->string('certificate_path')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specializations');
    }
};
