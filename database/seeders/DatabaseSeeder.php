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
            // Configuration tables first (core seeders)
            // DocumentTypeSeeder is now handled by EnsureDocumentTypesArePresent State
            // ExamTypeSeeder is now handled by ExamDatabaseSeeder
            LanguageSeeder::class,
            // ApplicationStatusSeeder is now handled by RegistrationDatabaseSeeder
            // PaymentTypesAndMethodsSeeder is now handled by PaymentDatabaseSeeder
            WorkflowStatesSeeder::class,
            // NotificationTemplatesSeeder is now handled by NotificationDatabaseSeeder
            UserProfilesSeeder::class,
            // CardTypesSeeder is now handled by CardDatabaseSeeder
            // ExamTypesSeeder is now handled by ExamDatabaseSeeder

            // Main entities (moved to modules)
            // UserSeeder is now handled by EnsureAdministratorIsPresent State
            // MemberSeeder is now handled by MemberDatabaseSeeder
            // RegistrationSeeder is now handled by RegistrationDatabaseSeeder
            // DocumentSeeder is now handled by DocumentDatabaseSeeder
            // ExamSeeder is now handled by ExamDatabaseSeeder
            // ExamApplicationSeeder is now handled by ExamDatabaseSeeder
            // ExamResultSeeder is now handled by ExamDatabaseSeeder
            // PaymentSeeder is now handled by PaymentDatabaseSeeder

            // Medical Residency Seeders (moved to modules)
            // ResidencyProgramSeeder is now handled by ResidencyDatabaseSeeder
            // ResidencyApplicationSeeder is now handled by ResidencyDatabaseSeeder
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
