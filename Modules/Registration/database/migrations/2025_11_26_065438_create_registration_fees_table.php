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
        Schema::create('registration_fees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Código único da taxa (ex: joia, quota_anual)');
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('category')->nullable()->comment('Categoria da taxa conforme Anexo B');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_fees');
    }
};
