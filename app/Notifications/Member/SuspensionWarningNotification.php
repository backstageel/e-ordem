<?php

namespace App\Notifications\Member;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspensionWarningNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Member $member,
        public int $daysUntilSuspension
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Aviso de Suspensão Iminente - ORMM')
            ->greeting('Olá, '.$notifiable->name)
            ->line('⚠️ ATENÇÃO: Sua conta será suspensa automaticamente se você não regularizar suas quotas.')
            ->line('**Faltam apenas '.$this->daysUntilSuspension.' dia(s)** para a suspensão automática.')
            ->line('Você possui quotas em atraso que precisam ser pagas urgentemente.')
            ->action('Ver Minhas Quotas', route('member.quotas.index'))
            ->line('Por favor, entre em contato com a administração se tiver alguma dúvida.');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Aviso de Suspensão',
            'message' => 'Sua conta será suspensa em '.$this->daysUntilSuspension.' dia(s) se não regularizar suas quotas',
            'type' => 'danger',
            'link' => route('member.quotas.index'),
        ];
    }
}
