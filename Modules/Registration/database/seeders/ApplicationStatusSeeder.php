<?php

namespace Modules\Registration\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed application statuses
        DB::table('application_statuses')->insert([
            ['name' => 'Draft', 'description' => 'Rascunho', 'color' => '#6c757d', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Submitted', 'description' => 'Submetido', 'color' => '#0d6efd', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Under Review', 'description' => 'Em Análise', 'color' => '#fd7e14', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Documents Pending', 'description' => 'Documentos Pendentes', 'color' => '#ffc107', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Approved', 'description' => 'Aprovado', 'color' => '#198754', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rejected', 'description' => 'Rejeitado', 'color' => '#dc3545', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cancelled', 'description' => 'Cancelado', 'color' => '#6c757d', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
