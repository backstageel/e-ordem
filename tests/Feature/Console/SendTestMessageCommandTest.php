<?php

use App\Models\Message;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('sends a test message between two users successfully', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();

    $this->artisan('app:send-test-message', [
        'sender' => $sender->id,
        'recipient' => $recipient->id,
    ])
        ->expectsOutput("Test message sent from {$sender->name} to {$recipient->name} successfully.")
        ->assertSuccessful();

    $this->assertDatabaseHas('messages', [
        'sender_id' => $sender->id,
        'recipient_id' => $recipient->id,
        'message' => 'This is a test message to verify the messaging system is working correctly.',
    ]);
});

it('fails when sender does not exist', function () {
    $recipient = User::factory()->create();

    $this->artisan('app:send-test-message', [
        'sender' => 99999,
        'recipient' => $recipient->id,
    ])
        ->expectsOutput('Sender with ID 99999 not found.')
        ->assertFailed();
});

it('fails when recipient does not exist', function () {
    $sender = User::factory()->create();

    $this->artisan('app:send-test-message', [
        'sender' => $sender->id,
        'recipient' => 99999,
    ])
        ->expectsOutput('Recipient with ID 99999 not found.')
        ->assertFailed();
});

it('creates a message record in the database', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();

    $this->artisan('app:send-test-message', [
        'sender' => $sender->id,
        'recipient' => $recipient->id,
    ])->assertSuccessful();

    $message = Message::where('sender_id', $sender->id)
        ->where('recipient_id', $recipient->id)
        ->first();

    expect($message)->not->toBeNull()
        ->and($message->message)->toBe('This is a test message to verify the messaging system is working correctly.');
});
