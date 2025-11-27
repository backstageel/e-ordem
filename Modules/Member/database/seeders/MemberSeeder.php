<?php

namespace Modules\Member\Database\Seeders;

use App\Models\MedicalSpeciality;
use App\Models\Member;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the user with email medico@hostmoz.net
        $medicoUser = User::where('email', 'medico@hostmoz.net')->first();

        // If the user exists, find or create a person for it
        if ($medicoUser) {
            $medicoPerson = Person::firstOrCreate(
                ['user_id' => $medicoUser->id],
                [
                    'name' => $medicoUser->name,
                    'email' => $medicoUser->email,
                    'civility' => 'Dr.',
                    'first_name' => 'Medico',
                    'last_name' => 'Hostmoz',
                    'gender_id' => 1, // Male
                    'birth_country_id' => 1, // Mozambique
                    'birth_province_id' => 1,
                    'birth_district_id' => 1,
                    'birth_date' => now()->subYears(35),
                    'marital_status_id' => 1, // Single
                    'identity_document_id' => 1, // BI (National ID)
                    'identity_document_number' => '123456789012345',
                    'nationality_id' => 1, // Mozambique
                    'identity_document_issue_date' => now()->subYears(5),
                    'identity_document_issue_place' => 'Maputo',
                    'identity_document_expiry_date' => now()->addYears(5),
                    'phone' => '+258841234567',
                    'mobile' => '+258851234567',
                    'living_address' => 'Av. Eduardo Mondlane, 123, Maputo',
                ]
            );

            // Get or create Cardiologia speciality
            $cardiologySpeciality = MedicalSpeciality::where('code', 'CAR')->first();
            if (! $cardiologySpeciality) {
                $cardiologySpeciality = MedicalSpeciality::where('name', 'Cardiologia')->first();
            }

            // Create a member linked to the person
            $member = Member::firstOrCreate(
                ['person_id' => $medicoPerson->id],
                [
                    'member_number' => 'MEM00001',
                    'registration_number' => 'MED00001',
                    'registration_date' => now()->subYears(5),
                    'expiry_date' => now()->addYears(5),
                    'professional_category' => 'Médico Especialista',
                    'specialty' => 'Cardiologia',
                    'sub_specialty' => 'Cardiologia Intervencionista',
                    'workplace' => 'Hospital Central de Maputo',
                    'workplace_address' => 'Av. Agostinho Neto, 123, Maputo',
                    'workplace_phone' => '+258211234567',
                    'workplace_email' => 'hospital.central@saude.gov.mz',
                    'academic_degree' => 'Doutoramento em Medicina',
                    'university' => 'Universidade Eduardo Mondlane',
                    'school_faculty' => 'Faculdade de Medicina',
                    'graduation_date' => now()->subYears(15),
                    'years_of_experience' => 10,
                    'detailed_experience' => 'Experiência em Cardiologia por 10 anos.',
                    'current_position' => 'Chefe de Serviço de Cardiologia',
                    'work_start_date' => now()->subYears(10),
                    'service_institution' => 'Hospital Central de Maputo',
                    'service_sector' => 'Público',
                    'application_date' => now()->subYears(5),
                    'entry_date' => now()->subYears(5),
                    'entry_category' => 'Especialista',
                    'professional_reference_1_name' => 'Dr. João Silva',
                    'professional_reference_1_phone' => '+258841234567',
                    'professional_reference_1_email' => 'joao.silva@hospital.gov.mz',
                    'professional_affiliations' => 'Sociedade Moçambicana de Cardiologia',
                    'languages_spoken' => json_encode(['Português', 'Inglês']),
                    'research_interests' => 'Cardiologia Intervencionista, Hipertensão Arterial',
                    'publications' => '5 artigos publicados em revistas internacionais',
                    'terms_accepted' => true,
                    'data_consent' => true,
                    'truth_declaration' => true,
                    'terms_accepted_date' => now()->subYears(5),
                    'status' => 'active',
                    'dues_paid' => true,
                    'dues_paid_until' => now()->addYears(1),
                    'notes' => 'Médico exemplar com excelente histórico profissional.',
                ]
            );

            // Attach medical speciality (primary)
            if ($cardiologySpeciality) {
                $member->medicalSpecialities()->syncWithoutDetaching([
                    $cardiologySpeciality->id => ['is_primary' => true],
                ]);
            }
        }

        // Common Mozambican first names
        $firstNames = [
            'António', 'Manuel', 'João', 'José', 'Carlos', 'Paulo', 'Fernando', 'Pedro', 'Luís', 'Francisco',
            'Ana', 'Maria', 'Isabel', 'Luísa', 'Carla', 'Sofia', 'Teresa', 'Joana', 'Catarina', 'Marta',
            'Amade', 'Mussá', 'Ibrahim', 'Issufo', 'Momade', 'Fátima', 'Aisha', 'Zainabo', 'Amina', 'Saíde',
        ];

        // Common Mozambican last names
        $lastNames = [
            'Silva', 'Santos', 'Ferreira', 'Pereira', 'Oliveira', 'Costa', 'Rodrigues', 'Martins', 'Fernandes', 'Gonçalves',
            'Almeida', 'Sousa', 'Lopes', 'Marques', 'Vieira', 'Dias', 'Mendes', 'Ribeiro', 'Pinto', 'Carvalho',
            'Mondlane', 'Machel', 'Chissano', 'Guebuza', 'Nyusi', 'Dhlakama', 'Simango', 'Mabjaia', 'Sitoe', 'Cossa',
        ];

        // Common Mozambican universities for medical education
        $universities = [
            'Universidade Eduardo Mondlane',
            'Universidade Católica de Moçambique',
            'Universidade Lúrio',
            'Instituto Superior de Ciências e Tecnologia de Moçambique',
            'Universidade Zambeze',
            'Universidade Pedagógica',
            'Universidade São Tomás de Moçambique',
            'Universidade Técnica de Moçambique',
            'Universidade Jean Piaget de Moçambique',
            'Universidade Politécnica',
        ];

        // Common medical specialties in Mozambique (names matching MedicalSpeciality table)
        $specialtyNames = [
            'Medicina Geral', 'Pediatria', 'Ginecologia e Obstetrícia', 'Cirurgia Geral', 'Medicina Interna',
            'Cardiologia', 'Ortopedia', 'Oftalmologia', 'Psiquiatria', 'Dermatologia',
            'Neurologia', 'Urologia', 'Otorrinolaringologia', 'Anestesiologia', 'Radiologia',
            'Medicina Familiar', 'Medicina Tropical', 'Pneumologia', 'Nefrologia', 'Oncologia',
        ];

        // Common sub-specialties
        $subSpecialties = [
            'Cardiologia Pediátrica', 'Cirurgia Cardíaca', 'Cirurgia Plástica', 'Neurocirurgia', 'Endocrinologia',
            'Gastroenterologia', 'Hematologia', 'Doenças Infecciosas', 'Medicina Intensiva', 'Reumatologia',
            '', '', '', '', '', // Some empty values for variety
        ];

        // Common workplaces in Mozambique
        $workplaces = [
            'Hospital Central de Maputo', 'Hospital Central da Beira', 'Hospital Central de Nampula',
            'Hospital Geral José Macamo', 'Hospital Geral de Mavalane', 'Hospital Provincial de Inhambane',
            'Hospital Provincial de Gaza', 'Hospital Provincial de Tete', 'Hospital Provincial de Quelimane',
            'Hospital Militar de Maputo', 'Hospital Privado de Maputo', 'Clínica Privada da Sommerschield',
            'Centro de Saúde 1º de Maio', 'Centro de Saúde de Polana Caniço', 'Centro de Saúde de Zimpeto',
            'Instituto Nacional de Saúde', 'Ministério da Saúde', 'Clínica da Universidade Eduardo Mondlane',
            'Hospital Psiquiátrico do Infulene', 'Centro de Saúde Mental de Nampula',
        ];

        // Professional categories
        $professionalCategories = [
            'Médico Generalista', 'Médico Especialista', 'Médico Residente', 'Médico Interno',
            'Médico Chefe de Serviço', 'Médico Diretor Clínico', 'Médico Consultor', 'Médico Investigador',
            'Médico Professor', 'Médico Gestor de Saúde',
        ];

        // Academic degrees
        $academicDegrees = [
            'Licenciatura em Medicina', 'Mestrado em Saúde Pública', 'Doutoramento em Medicina',
            'Pós-Graduação em Medicina Tropical', 'Especialização em Cirurgia', 'Mestrado em Epidemiologia',
            'Doutoramento em Ciências Biomédicas', 'Mestrado em Gestão Hospitalar',
        ];

        // Create 30 members
        for ($i = 1; $i < 30; $i++) {
            // Generate person data
            $gender = rand(1, 2); // 1 = Male, 2 = Female
            $firstName = $firstNames[array_rand($firstNames)];
            $middleName = rand(0, 1) == 1 ? $firstNames[array_rand($firstNames)] : null; // 50% chance to have middle name
            $lastName = $lastNames[array_rand($lastNames)];
            $email = strtolower(Str::slug($firstName.'.'.$lastName, '.')).'.'.$i.'@example.com';

            // Check if a user with this email already exists
            $user = User::where('email', $email)->first();

            // If no user exists, create one
            if (! $user) {
                $user = User::create([
                    'name' => $firstName.' '.($middleName ? $middleName.' ' : '').$lastName,
                    'email' => $email,
                    'password' => Hash::make('12345678'),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]);
            }

            $userId = $user->id;

            $person = Person::create([
                'user_id' => $userId,
                'civility' => $gender == 1 ? 'Sr.' : 'Sra.',
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'name' => $firstName.' '.($middleName ? $middleName.' ' : '').$lastName,
                'father_name' => $lastNames[array_rand($lastNames)].', '.$firstNames[array_rand($firstNames)],
                'mother_name' => $lastNames[array_rand($lastNames)].', '.$firstNames[array_rand($firstNames)],
                'gender_id' => $gender,
                'birth_country_id' => 1, // Mozambique
                'birth_province_id' => rand(1, 11), // Mozambique has 11 provinces
                'birth_district_id' => rand(1, 128), // Approximate number of districts
                'birth_date' => now()->subYears(rand(25, 65))->subDays(rand(0, 365)),
                'marital_status_id' => rand(1, 4), // 1=Single, 2=Married, 3=Divorced, 4=Widowed
                'identity_document_id' => 1, // 1=BI (National ID)
                'identity_document_number' => rand(10000000, 99999999).rand(10000000, 99999999),
                'nationality_id' => 1, // Mozambique
                'identity_document_issue_date' => now()->subYears(rand(1, 10)),
                'identity_document_issue_place' => ['Maputo', 'Beira', 'Nampula', 'Quelimane', 'Tete'][rand(0, 4)],
                'identity_document_expiry_date' => now()->addYears(rand(1, 5)),
                'has_disability' => rand(0, 10) == 0, // 10% chance of having a disability
                'disability_description' => null,
                'phone' => '+258'.rand(82, 87).rand(1000000, 9999999),
                'email' => strtolower(Str::slug($firstName.'.'.$lastName, '.')).'.'.$i.'@example.com',
                'mobile' => '+258'.rand(82, 87).rand(1000000, 9999999),
                'living_address' => 'Av. '.$lastNames[array_rand($lastNames)].', '.rand(1, 999).', '.['Maputo', 'Beira', 'Nampula', 'Quelimane', 'Tete'][rand(0, 4)],
            ]);

            // If person has disability, add description
            if ($person->has_disability) {
                $disabilities = ['Deficiência visual', 'Deficiência auditiva', 'Deficiência motora', 'Deficiência cognitiva'];
                $person->disability_description = $disabilities[rand(0, 3)];
                $person->save();
            }

            // Create a member linked to the person
            $specialtyName = $specialtyNames[array_rand($specialtyNames)];
            // Get medical speciality from database
            $primarySpeciality = MedicalSpeciality::where('name', $specialtyName)->first();
            // If not found, get a random one
            if (! $primarySpeciality) {
                $primarySpeciality = MedicalSpeciality::inRandomOrder()->first();
            }

            // Determine if member has multiple specialities (30% chance)
            $hasMultipleSpecialities = rand(1, 100) <= 30;
            $additionalSpecialities = [];
            if ($hasMultipleSpecialities && $primarySpeciality) {
                // Get 1-2 additional specialities (excluding the primary one)
                $availableSpecialities = MedicalSpeciality::where('id', '!=', $primarySpeciality->id)
                    ->inRandomOrder()
                    ->limit(rand(1, 2))
                    ->get();
                $additionalSpecialities = $availableSpecialities->pluck('id')->toArray();
            }

            $subSpecialty = rand(0, 1) == 1 ? $subSpecialties[array_rand($subSpecialties)] : null; // 50% chance to have sub-specialty
            $workplace = $workplaces[array_rand($workplaces)];
            $professionalCategory = $professionalCategories[array_rand($professionalCategories)];
            $academicDegree = $academicDegrees[array_rand($academicDegrees)];
            $university = $universities[array_rand($universities)];
            $graduationDate = now()->subYears(rand(1, 30));
            $yearsOfExperience = (int) max(0, now()->diffInYears($graduationDate));

            $member = Member::create([
                'person_id' => $person->id,
                'member_number' => 'MEM'.str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'registration_number' => 'MED'.str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'registration_date' => now()->subYears(rand(1, 10)),
                'expiry_date' => now()->addYears(rand(1, 5)),
                'professional_category' => $professionalCategory,
                'specialty' => $specialtyName,
                'sub_specialty' => $subSpecialty,
                'workplace' => $workplace,
                'workplace_address' => 'Av. '.$lastNames[array_rand($lastNames)].', '.rand(1, 999).', '.['Maputo', 'Beira', 'Nampula', 'Quelimane', 'Tete'][rand(0, 4)],
                'workplace_phone' => '+258'.rand(21, 29).rand(100000, 999999),
                'workplace_email' => strtolower(Str::slug($workplace, '.')).'@saude.gov.mz',
                'academic_degree' => $academicDegree,
                'university' => $university,
                'school_faculty' => 'Faculdade de Medicina',
                'graduation_date' => $graduationDate,
                'years_of_experience' => $yearsOfExperience,
                'previous_license_number' => rand(0, 1) == 1 ? 'OLD-'.rand(10000, 99999) : null,
                'detailed_experience' => rand(0, 1) == 1 ? 'Experiência em '.$specialtyName.' por '.$yearsOfExperience.' anos.' : null,
                'current_position' => rand(0, 1) == 1 ? ['Médico', 'Chefe de Serviço', 'Diretor Clínico', 'Consultor'][rand(0, 3)] : null,
                'work_start_date' => now()->subYears(rand(1, $yearsOfExperience)),
                'service_institution' => $workplace,
                'service_sector' => rand(0, 1) == 1 ? 'Público' : 'Privado',
                'application_date' => now()->subYears(rand(1, 10)),
                'entry_date' => now()->subYears(rand(1, 10)),
                'entry_category' => rand(0, 1) == 1 ? ['Generalista', 'Especialista', 'Residente'][rand(0, 2)] : null,
                'professional_reference_1_name' => rand(0, 1) == 1 ? 'Dr. '.$firstNames[array_rand($firstNames)].' '.$lastNames[array_rand($lastNames)] : null,
                'professional_reference_1_phone' => rand(0, 1) == 1 ? '+258'.rand(82, 87).rand(1000000, 9999999) : null,
                'professional_reference_1_email' => rand(0, 1) == 1 ? 'ref1@example.com' : null,
                'professional_affiliations' => rand(0, 1) == 1 ? 'Sociedade Moçambicana de '.$specialtyName : null,
                'languages_spoken' => json_encode(['Português', 'Inglês']),
                'research_interests' => rand(0, 1) == 1 ? 'Pesquisa em '.$specialtyName : null,
                'publications' => rand(0, 1) == 1 ? rand(1, 10).' artigos publicados' : null,
                'terms_accepted' => true,
                'data_consent' => true,
                'truth_declaration' => true,
                'terms_accepted_date' => now()->subYears(rand(1, 10)),
                'status' => ['active', 'inactive', 'suspended'][rand(0, 2)],
                'dues_paid' => rand(0, 1),
                'dues_paid_until' => rand(0, 1) == 1 ? now()->addMonths(rand(1, 24)) : null,
                'notes' => rand(0, 1) == 1 ? 'Observações sobre o membro: '.$firstName.' '.$lastName : null,
            ]);

            // Attach medical specialities
            if ($primarySpeciality) {
                $specialitiesToAttach = [
                    $primarySpeciality->id => ['is_primary' => true],
                ];

                // Add additional specialities as non-primary
                foreach ($additionalSpecialities as $specialityId) {
                    $specialitiesToAttach[$specialityId] = ['is_primary' => false];
                }

                $member->medicalSpecialities()->sync($specialitiesToAttach);
            }
        }
    }
}
