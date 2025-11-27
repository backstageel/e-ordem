<?php

namespace Database\Seeders;

use App\Models\ResidenceLocation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ResidenceLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Hospital Central de Maputo',
                'address' => 'Av. Agostinho Neto, 1364',
                'city' => 'Maputo',
                'province' => 'Maputo',
                'phone' => '+258 21 492668',
                'email' => 'hcm@misau.gov.mz',
                'capacity' => 50,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hospital Central da Beira',
                'address' => 'Av. Mártires da Revolução',
                'city' => 'Beira',
                'province' => 'Sofala',
                'phone' => '+258 23 312705',
                'email' => 'hcb@misau.gov.mz',
                'capacity' => 30,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hospital Central de Nampula',
                'address' => 'Av. Eduardo Mondlane',
                'city' => 'Nampula',
                'province' => 'Nampula',
                'phone' => '+258 26 212260',
                'email' => 'hcn@misau.gov.mz',
                'capacity' => 25,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hospital Geral José Macamo',
                'address' => 'Av. OUA, Bairro do Aeroporto',
                'city' => 'Maputo',
                'province' => 'Maputo',
                'phone' => '+258 21 400045',
                'email' => 'hjm@misau.gov.mz',
                'capacity' => 20,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hospital Geral de Mavalane',
                'address' => 'Av. FPLM, Bairro de Mavalane',
                'city' => 'Maputo',
                'province' => 'Maputo',
                'phone' => '+258 21 465002',
                'email' => 'hgm@misau.gov.mz',
                'capacity' => 15,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Instituto do Coração de Maputo',
                'address' => 'Rua das Acácias, 45',
                'city' => 'Maputo',
                'province' => 'Maputo',
                'phone' => '+258 21 498765',
                'email' => 'icor@misau.gov.mz',
                'capacity' => 10,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hospital Provincial de Inhambane',
                'address' => 'Rua Principal',
                'city' => 'Inhambane',
                'province' => 'Inhambane',
                'phone' => '+258 29 320487',
                'email' => 'hpi@misau.gov.mz',
                'capacity' => 15,
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hospital Provincial de Tete',
                'address' => 'Av. da Liberdade',
                'city' => 'Tete',
                'province' => 'Tete',
                'phone' => '+258 25 223067',
                'email' => 'hpt@misau.gov.mz',
                'capacity' => 12,
                'status' => 'inactive',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($locations as $location) {
            ResidenceLocation::create($location);
        }
    }
}
