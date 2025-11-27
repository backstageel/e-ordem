<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NeighborhoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some districts to create neighborhoods for
        $districts = DB::table('districts')->limit(5)->get();

        if ($districts->isEmpty()) {
            $this->command->warn('No districts found. Please run the district seeder first.');

            return;
        }

        $neighborhoods = [];

        foreach ($districts as $district) {
            // Create 3-5 neighborhoods per district
            $neighborhoodCount = rand(3, 5);

            for ($i = 1; $i <= $neighborhoodCount; $i++) {
                $neighborhoods[] = [
                    'name' => "Bairro {$i} - {$district->name}",
                    'code' => "B{$district->id}{$i}",
                    'district_id' => $district->id,
                    'description' => "Bairro {$i} localizado no distrito de {$district->name}",
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('neighborhoods')->insert($neighborhoods);

        $this->command->info('Created '.count($neighborhoods).' neighborhoods.');
    }
}
