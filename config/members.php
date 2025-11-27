<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Member Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the Members module.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Quota Configuration
    |--------------------------------------------------------------------------
    */
    'quota' => [
        // Default monthly quota amount in MZN
        'default_amount' => env('MEMBER_QUOTA_DEFAULT_AMOUNT', 4000.00),

        // Days after due date before quota is considered overdue
        'overdue_days' => env('MEMBER_QUOTA_OVERDUE_DAYS', 0),

        // Days of grace period before showing warning
        'grace_period_days' => env('MEMBER_QUOTA_GRACE_PERIOD_DAYS', 30),

        // Penalty percentage for overdue quotas (e.g., 0.5 = 50%)
        'penalty_percentage' => env('MEMBER_QUOTA_PENALTY_PERCENTAGE', 0.5),

        // Days after due date before suspension
        'suspension_days' => env('MEMBER_QUOTA_SUSPENSION_DAYS', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Update Configuration
    |--------------------------------------------------------------------------
    */
    'profile' => [
        // Years between mandatory profile updates
        'update_interval_years' => env('MEMBER_PROFILE_UPDATE_INTERVAL', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Document Configuration
    |--------------------------------------------------------------------------
    */
    'documents' => [
        // Days before document expiry to send alert
        'expiry_alert_days' => env('MEMBER_DOCUMENT_EXPIRY_ALERT_DAYS', 30),

        // Days after document expiry before considering it expired
        'expiry_grace_days' => env('MEMBER_DOCUMENT_EXPIRY_GRACE_DAYS', 0),
    ],

    /*
    |--------------------------------------------------------------------------
    | Card Configuration
    |--------------------------------------------------------------------------
    */
    'card' => [
        // Default card validity period in months
        'validity_months' => env('MEMBER_CARD_VALIDITY_MONTHS', 12),

        // Require active status for card generation
        'require_active_status' => env('MEMBER_CARD_REQUIRE_ACTIVE', true),

        // Require regular quotas for card generation
        'require_regular_quotas' => env('MEMBER_CARD_REQUIRE_REGULAR_QUOTAS', true),

        // Require no pending documents for card generation
        'require_no_pending_documents' => env('MEMBER_CARD_REQUIRE_NO_PENDING_DOCS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Automatic Suspension Configuration
    |--------------------------------------------------------------------------
    */
    'suspension' => [
        // Enable automatic suspension for overdue quotas
        'auto_suspend_enabled' => env('MEMBER_AUTO_SUSPEND_ENABLED', true),

        // Days of overdue quotas before automatic suspension
        'days_before_suspension' => env('MEMBER_SUSPENSION_DAYS', 90),

        // Days before suspension to send warning notification
        'warning_days_before' => env('MEMBER_SUSPENSION_WARNING_DAYS', 7),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        // Send quota reminders
        'quota_reminders_enabled' => env('MEMBER_QUOTA_REMINDERS_ENABLED', true),

        // Days before quota due date to send reminder
        'quota_reminder_days_before' => env('MEMBER_QUOTA_REMINDER_DAYS', 15),

        // Send document expiry alerts
        'document_expiry_alerts_enabled' => env('MEMBER_DOC_EXPIRY_ALERTS_ENABLED', true),

        // Send compliance alerts
        'compliance_alerts_enabled' => env('MEMBER_COMPLIANCE_ALERTS_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination Configuration
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        // Default number of members per page
        'per_page' => env('MEMBER_PAGINATION_PER_PAGE', 20),
    ],
];
