<?php

namespace Modules\Card\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed card types
        DB::table('card_types')->insert([
            [
                'name' => 'Provisional Member Card',
                'description' => 'Card for provisional members',
                'color_code' => '#FFC107', // Amber
                'validity_period_days' => 365,
                'fee' => 300.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Full Member Card',
                'description' => 'Card for full members',
                'color_code' => '#4CAF50', // Green
                'validity_period_days' => 730,
                'fee' => 500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Specialist Member Card',
                'description' => 'Card for specialist members',
                'color_code' => '#2196F3', // Blue
                'validity_period_days' => 730,
                'fee' => 600.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
