<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add foreign key to document_checklists table
     */
    public function up(): void
    {
        if (Schema::hasTable('document_checklists') && Schema::hasColumn('document_checklists', 'registration_type_id')) {
            Schema::table('document_checklists', function (Blueprint $table) {
                $table->foreign('registration_type_id')
                    ->references('id')
                    ->on('registration_types')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('document_checklists') && Schema::hasColumn('document_checklists', 'registration_type_id')) {
            Schema::table('document_checklists', function (Blueprint $table) {
                try {
                    $table->dropForeign(['document_checklists_registration_type_id_foreign']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, ignore
                }
            });
        }
    }
};
