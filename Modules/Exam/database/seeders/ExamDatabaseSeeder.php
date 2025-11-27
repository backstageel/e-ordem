<?php

namespace Modules\Exam\Database\Seeders;

use Illuminate\Database\Seeder;

class ExamDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            ExamTypeSeeder::class,
            ExamTypesSeeder::class,
            ExamSeeder::class,
            ExamApplicationSeeder::class,
            ExamResultSeeder::class,
        ]);
    }
}
