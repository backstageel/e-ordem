<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ResidenceMedicalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Call seeders in the correct order
        $this->call([
            ResidenceProgramSeeder::class,
            ResidenceLocationSeeder::class,
            ResidenceApplicationSeeder::class,
            ResidenceResidentSeeder::class,
            ResidenceEvaluationSeeder::class,
            ResidenceExamSeeder::class,
            ResidenceCompletionSeeder::class,
        ]);
    }
}
