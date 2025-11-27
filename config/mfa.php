<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MFA Settings
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration settings for Multi-Factor Authentication.
    |
    */

    // Whether MFA is enabled globally for the application
    'enabled' => env('MFA_ENABLED', true),

    // Whether MFA is required for all users or optional
    'required' => env('MFA_REQUIRED', false),

    // The number of recovery codes to generate for each user
    'recovery_codes_count' => 8,

    // The length of each recovery code
    'recovery_code_length' => 10,

    // The window in which a TOTP code is valid (in seconds)
    'window' => 30,
];
