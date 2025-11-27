<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed languages
        DB::table('languages')->insert([
            ['name' => 'Português', 'code' => 'por', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'English', 'code' => 'eng', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Français', 'code' => 'fra', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Español', 'code' => 'spa', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Deutsch', 'code' => 'deu', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Italiano', 'code' => 'ita', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'العربية', 'code' => 'ara', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '中文', 'code' => 'zho', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
