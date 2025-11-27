<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing exam types
        DB::table('exam_types')->truncate();

        // Seed exam types
        DB::table('exam_types')->insert([
            [
                'name' => 'Certification Exam',
                'description' => 'Exam for medical certification',
                'fee' => 1500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Specialty Exam',
                'description' => 'Exam for medical specialty certification',
                'fee' => 2000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Recertification Exam',
                'description' => 'Exam for medical recertification',
                'fee' => 1000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Foreign Medical Graduate Exam',
                'description' => 'Exam for foreign medical graduates',
                'fee' => 2500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
