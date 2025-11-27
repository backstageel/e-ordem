<?php

namespace Modules\Notification\Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'sender_id' => User::factory(),
            'recipient_id' => User::factory(),
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'read' => $this->faker->boolean(),
            'read_at' => $this->faker->optional()->dateTime(),
        ];
    }
}
