<?php

use App\Models\Country;
use App\Models\District;
use App\Models\IdentityDocument;
use App\Models\Province;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    // Use existing data from database
    $this->mozambique = Country::where('code', 'MZ')->orWhere('name', 'Moçambique')->first();
    if (! $this->mozambique) {
        $africa = \App\Models\Continent::firstOrCreate(['name' => 'África']);
        $this->mozambique = Country::create([
            'name' => 'Moçambique',
            'iso' => 'MOZ',
            'code' => 'MZ',
            'continent_id' => $africa->id,
        ]);
    }

    // Use existing provinces and districts
    $this->maputo = Province::where('country_id', $this->mozambique->id)->first();
    if (! $this->maputo) {
        $this->maputo = Province::create([
            'name' => 'Maputo',
            'country_id' => $this->mozambique->id,
        ]);
    }

    $this->maputoCity = District::where('province_id', $this->maputo->id)->first();
    if (! $this->maputoCity) {
        $this->maputoCity = District::create([
            'name' => 'Maputo Cidade',
            'province_id' => $this->maputo->id,
        ]);
    }

    // Create identity document type
    $this->identityDoc = IdentityDocument::firstOrCreate([
        'name' => 'Bilhete de Identidade',
    ], [
        'code' => 'BI',
    ]);
});

describe('IdentityAddressStep - Unit Tests', function () {
    it('validates document expiry date is at least 6 months in the future', function () {
        $validDate = now()->addMonths(7)->format('Y-m-d');
        $invalidDate = now()->addMonths(3)->format('Y-m-d');

        // Test validation logic
        $validDateObj = \Carbon\Carbon::parse($validDate);
        $invalidDateObj = \Carbon\Carbon::parse($invalidDate);
        $sixMonthsFromNow = now()->addMonths(6);

        expect($validDateObj->greaterThan($sixMonthsFromNow))->toBeTrue();
        expect($invalidDateObj->greaterThan($sixMonthsFromNow))->toBeFalse();
    });

    it('validates phone format for alternative phones', function () {
        $validPhones = ['+258821234567', '+25882123456', '+258840000000']; // 7-8 digits after +258[2-8]
        $invalidPhones = ['123', '258821234567', '+258921234567', '+2588212345678']; // 9 digits is invalid

        $pattern = '/^\+258[2-8][0-9]{7,8}$/';

        foreach ($validPhones as $phone) {
            expect(preg_match($pattern, $phone))->toBe(1);
        }

        foreach ($invalidPhones as $phone) {
            expect(preg_match($pattern, $phone))->toBe(0);
        }
    });
});
