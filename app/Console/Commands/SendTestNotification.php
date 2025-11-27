<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Console\Command;

class SendTestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-test-notification {user : The ID of the user to send the notification to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test notification to a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user');

        $user = User::find($userId);

        if (! $user) {
            $this->error("User with ID {$userId} not found.");

            return 1;
        }

        $notification = new GeneralNotification(
            title: 'Test Notification',
            message: 'This is a test notification to verify the notification system is working correctly.',
            link: dashboard_route(),
            icon: 'fa-bell'
        );

        $user->notify($notification);

        $this->info("Test notification sent to {$user->name} successfully.");

        return 0;
    }
}
