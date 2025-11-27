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
        // Add foreign key constraint after members table is created
        // Check if table exists first (it's created in core migrations)
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
        Schema::table('specializations', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
        });
    }
};
