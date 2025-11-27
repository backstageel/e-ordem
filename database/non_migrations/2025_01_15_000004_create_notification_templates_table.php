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
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // email, sms, push
            $table->string('module'); // registration, exam, residence, etc.
            $table->string('event'); // created, updated, approved, rejected, etc.
            $table->string('subject')->nullable(); // For email
            $table->text('body');
            $table->json('variables')->nullable(); // Available variables for the template
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['module', 'event', 'type']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
