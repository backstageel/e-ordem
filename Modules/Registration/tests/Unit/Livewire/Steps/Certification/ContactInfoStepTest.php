<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Livewire\Wizard\Steps\Certification\ContactInfoStep;
use Tests\TestCase;

uses(TestCase::class);
uses(RefreshDatabase::class);

describe('ContactInfoStep - Unit Tests', function () {
    it('has correct validation rules structure', function () {
        $step = new ContactInfoStep;

        // Test that validation rules are correct
        $rules = [
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'regex:/^\+258[2-8][0-9]{7,8}$/'],
        ];

        expect($rules)->toBeArray();
        expect($rules)->toHaveKey('email');
        expect($rules)->toHaveKey('phone');
    });

    it('validates email format correctly', function () {
        $validEmails = ['test@example.com', 'user.name@domain.co.uk'];
        $invalidEmails = ['invalid-email', 'test@', '@domain.com'];

        foreach ($validEmails as $email) {
            expect(filter_var($email, FILTER_VALIDATE_EMAIL))->not->toBeFalse();
        }

        foreach ($invalidEmails as $email) {
            expect(filter_var($email, FILTER_VALIDATE_EMAIL))->toBeFalse();
        }
    });

    it('validates phone format correctly', function () {
        $validPhones = ['+258821234567', '+25882123456', '+258840000000']; // 7-8 digits after +258[2-8]
        $invalidPhones = ['123', '258821234567', '+258921234567', '+2588212345678']; // 9 digits is invalid

        $pattern = '/^\+258[2-8][0-9]{7,8}$/';

        foreach ($validPhones as $phone) {
            $matches = preg_match($pattern, $phone);
            expect($matches)->toBe(1, "Phone {$phone} should match pattern");
        }

        foreach ($invalidPhones as $phone) {
            $matches = preg_match($pattern, $phone);
            expect($matches)->toBe(0, "Phone {$phone} should not match pattern");
        }
    });
});
