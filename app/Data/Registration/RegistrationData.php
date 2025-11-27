<?php

namespace App\Data\Registration;

use Spatie\LaravelData\Data;

class RegistrationData extends Data
{
    public function __construct(
        public ?int $registration_type_id,
        public array $contact,
        public array $personal,
        public array $identity,
        public array $academic,
        public array $uploads = [],
    ) {
    }
}


