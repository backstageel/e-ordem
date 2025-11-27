<?php

namespace App\Notifications\Member;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspensionNotification extends Notification
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
            ->subject('Conta Suspensa - ORMM')
            ->greeting('Olá, '.$notifiable->name)
            ->line('Sua conta de membro da ORMM foi suspensa.')
            ->line('**Motivo:** '.$this->reason)
            ->line('Para reativar sua conta, entre em contato com a administração.')
            ->action('Acessar Portal', url('/member'))
            ->line('Obrigado por usar o nosso sistema.');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Conta Suspensa',
            'message' => 'Sua conta foi suspensa. Motivo: '.$this->reason,
            'type' => 'warning',
            'link' => route('member.dashboard.index'),
        ];
    }
}
