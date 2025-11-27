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
        Schema::create('system_kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('category'); // members, registrations, payments, exams, etc.
            $table->string('type'); // count, sum, average, percentage, etc.
            $table->json('value'); // The KPI value
            $table->string('unit')->nullable(); // MZN, days, %, etc.
            $table->date('period_start');
            $table->date('period_end');
            $table->string('period_type'); // daily, weekly, monthly, yearly
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();

            $table->index(['category', 'period_type']);
            $table->index(['period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_kpis');
    }
};
