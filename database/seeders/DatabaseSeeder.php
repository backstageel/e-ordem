<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Nwidart\Modules\Facades\Module;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Ensure database states are loaded (RegistrationTypes, DocumentTypes, etc.)
        \Artisan::call('ensure-database-state-is-loaded');

        $this->call([
            // Configuration tables first
            // DocumentTypeSeeder is now handled by EnsureDocumentTypesArePresent State
            ExamTypeSeeder::class,
            LanguageSeeder::class,
            ApplicationStatusSeeder::class,
            PaymentTypesAndMethodsSeeder::class,
            WorkflowStatesSeeder::class,
            NotificationTemplatesSeeder::class,
            UserProfilesSeeder::class,
            CardTypesSeeder::class,
            ExamTypesSeeder::class,

            // Main entities
            // UserSeeder is now handled by EnsureAdministratorIsPresent State
            MemberSeeder::class,
            // RegistrationSeeder is now handled by RegistrationDatabaseSeeder
            DocumentSeeder::class,
            ExamSeeder::class,
            ExamApplicationSeeder::class,
            ExamResultSeeder::class,
            PaymentSeeder::class,

            // Medical Residency Seeders
            ResidencyProgramSeeder::class,
            ResidencyApplicationSeeder::class,
            // MedicalSpecialitySeeder is now handled by EnsureInitialConfigIsDone State
        ]);

        foreach (Module::allEnabled() as $module) {
            $seederClass = 'Modules\\'.$module->getName().'\\Database\\Seeders\\'.$module->getName().'DatabaseSeeder';
            if (class_exists($seederClass)) {
                $this->call($seederClass);
            }
        }
    }
}
