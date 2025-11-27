<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Registration\Models\TemporaryRegistration;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

beforeEach(function () {
    $this->tempRegistration = TemporaryRegistration::create([
        'email' => 'test@example.com',
        'phone' => '+258849902058',
        'registration_type' => 'certification',
        'current_step' => 3,
        'step_data' => [
            1 => ['field1' => 'value1'],
            2 => ['field2' => 'value2'],
            3 => ['field3' => 'value3'],
        ],
        'expires_at' => now()->addHours(24),
    ]);
});

describe('Expiration', function () {
    it('checks if temporary registration is expired', function () {
        $this->tempRegistration->update(['expires_at' => now()->subHour()]);
        expect($this->tempRegistration->isExpired())->toBeTrue();

        $this->tempRegistration->update(['expires_at' => now()->addHour()]);
        expect($this->tempRegistration->isExpired())->toBeFalse();
    });

    it('extends expiration time', function () {
        $originalExpiry = $this->tempRegistration->expires_at;
        $this->tempRegistration->extendExpiration(48);

        expect($this->tempRegistration->expires_at->gt($originalExpiry))->toBeTrue();
    });
});

describe('Step Data', function () {
    it('gets data for a specific step', function () {
        $stepData = $this->tempRegistration->getStepData(2);
        expect($stepData)->toBe(['field2' => 'value2']);
    });

    it('returns null for non-existent step', function () {
        $stepData = $this->tempRegistration->getStepData(99);
        expect($stepData)->toBeNull();
    });

    it('sets data for a specific step', function () {
        $this->tempRegistration->setStepData(4, ['field4' => 'value4']);

        expect($this->tempRegistration->getStepData(4))->toBe(['field4' => 'value4']);
    });

    it('updates existing step data', function () {
        $this->tempRegistration->setStepData(2, ['field2' => 'updated_value']);

        expect($this->tempRegistration->getStepData(2))->toBe(['field2' => 'updated_value']);
    });
});

describe('Find or Create by Contact', function () {
    it('finds existing temporary registration by email', function () {
        $found = TemporaryRegistration::findOrCreateByContact(
            'test@example.com',
            '+258849902059',
            'certification'
        );

        expect($found->id)->toBe($this->tempRegistration->id);
    });

    it('finds existing temporary registration by phone', function () {
        $found = TemporaryRegistration::findOrCreateByContact(
            'other@example.com',
            '+258849902058',
            'certification'
        );

        expect($found->id)->toBe($this->tempRegistration->id);
    });

    it('creates new temporary registration if not found', function () {
        $found = TemporaryRegistration::findOrCreateByContact(
            'new@example.com',
            '+258849902060',
            'provisional'
        );

        expect($found->id)->not->toBe($this->tempRegistration->id);
        expect($found->email)->toBe('new@example.com');
        expect($found->phone)->toBe('+258849902060');
        expect($found->registration_type)->toBe('provisional');
    });

    it('creates new temporary registration if expired', function () {
        $this->tempRegistration->update(['expires_at' => now()->subHour()]);

        $found = TemporaryRegistration::findOrCreateByContact(
            'test@example.com',
            '+258849902058',
            'certification'
        );

        expect($found->id)->not->toBe($this->tempRegistration->id);
    });
});

describe('Scopes', function () {
    it('filters not expired temporary registrations', function () {
        TemporaryRegistration::create([
            'email' => 'expired@example.com',
            'phone' => '+258849902061',
            'registration_type' => 'certification',
            'expires_at' => now()->subHour(),
        ]);

        TemporaryRegistration::create([
            'email' => 'active@example.com',
            'phone' => '+258849902062',
            'registration_type' => 'certification',
            'expires_at' => now()->addHour(),
        ]);

        expect(TemporaryRegistration::notExpired()->count())->toBeGreaterThanOrEqual(1);
    });
});
