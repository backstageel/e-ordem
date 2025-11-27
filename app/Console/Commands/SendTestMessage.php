<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Models\User;
use Illuminate\Console\Command;

class SendTestMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-test-message {sender : The ID of the user sending the message} {recipient : The ID of the user receiving the message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test message from one user to another';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $senderId = $this->argument('sender');
        $recipientId = $this->argument('recipient');

        $sender = User::find($senderId);
        $recipient = User::find($recipientId);

        if (! $sender) {
            $this->error("Sender with ID {$senderId} not found.");

            return 1;
        }

        if (! $recipient) {
            $this->error("Recipient with ID {$recipientId} not found.");

            return 1;
        }

        $message = new Message([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'message' => 'This is a test message to verify the messaging system is working correctly.',
        ]);

        $message->save();

        $this->info("Test message sent from {$sender->name} to {$recipient->name} successfully.");

        return 0;
    }
}
