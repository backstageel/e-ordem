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
        Schema::create('registration_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('category')->default('provisional');
            $table->string('payment_type_code');
            $table->string('description')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->integer('validity_period_days')->nullable();
            $table->boolean('renewable')->default(false);
            $table->integer('max_renewals')->default(0);
            $table->json('required_documents')->nullable();
            $table->json('eligibility_criteria')->nullable();
            $table->json('workflow_steps')->nullable();
            $table->boolean('is_active')->default(true);

            // Campos para discriminação de tipo
            $table->tinyInteger('category_number')->nullable()->comment('1, 2, 3 para certification');
            $table->tinyInteger('subtype_number')->nullable()->comment('1-12 para provisional');
            $table->string('grade')->nullable()->comment('A, B, C para effective');

            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_types');
    }
};
