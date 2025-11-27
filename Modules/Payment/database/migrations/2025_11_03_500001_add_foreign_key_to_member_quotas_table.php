<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add foreign key to member_quotas table
     */
    public function up(): void
    {
        if (Schema::hasTable('member_quotas') && Schema::hasColumn('member_quotas', 'payment_id')) {
            Schema::table('member_quotas', function (Blueprint $table) {
                $table->foreign('payment_id')
                    ->references('id')
                    ->on('payments')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('member_quotas') && Schema::hasColumn('member_quotas', 'payment_id')) {
            Schema::table('member_quotas', function (Blueprint $table) {
                try {
                    $table->dropForeign(['member_quotas_payment_id_foreign']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, ignore
                }
            });
        }
    }
};
