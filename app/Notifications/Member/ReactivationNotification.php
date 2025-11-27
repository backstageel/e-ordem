<?php

namespace App\Notifications\Member;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReactivationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Member $member,
        public string $reason
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Conta Reativada - ORMM')
            ->greeting('Olá, '.$notifiable->name)
            ->line('Sua conta de membro da ORMM foi reativada com sucesso.')
            ->line('**Motivo:** '.$this->reason)
            ->action('Acessar Portal', url('/member'))
            ->line('Bem-vindo de volta!');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Conta Reativada',
            'message' => 'Sua conta foi reativada. '.$this->reason,
            'type' => 'success',
            'link' => route('member.dashboard.index'),
        ];
    }
}
