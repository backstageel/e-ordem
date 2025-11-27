<?php

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

it('sends a test notification to a user successfully', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->artisan('app:send-test-notification', [
        'user' => $user->id,
    ])
        ->expectsOutput("Test notification sent to {$user->name} successfully.")
        ->assertSuccessful();

    Notification::assertSentTo($user, GeneralNotification::class, function ($notification) {
        return $notification->title === 'Test Notification'
            && $notification->message === 'This is a test notification to verify the notification system is working correctly.';
    });
});

it('fails when user does not exist', function () {
    $this->artisan('app:send-test-notification', [
        'user' => 99999,
    ])
        ->expectsOutput('User with ID 99999 not found.')
        ->assertFailed();
});

it('sends notification with correct properties', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->artisan('app:send-test-notification', [
        'user' => $user->id,
    ])->assertSuccessful();

    Notification::assertSentTo($user, GeneralNotification::class, function ($notification) {
        return $notification->title === 'Test Notification'
            && $notification->message === 'This is a test notification to verify the notification system is working correctly.'
            && $notification->link === route('dashboard')
            && $notification->icon === 'fa-bell';
    });
});

