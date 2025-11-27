<?php

namespace App\Notifications\Member;

use App\Models\Member;
use App\Models\MemberQuota;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotaReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Member $member,
        public MemberQuota $quota
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $daysUntilDue = now()->diffInDays($this->quota->due_date);

        return (new MailMessage)
            ->subject('Lembrete de Quota - ORMM')
            ->greeting('Olá, '.$notifiable->name)
            ->line('Este é um lembrete de que sua quota mensal está próxima do vencimento.')
            ->line('**Período:** '.$this->quota->period)
            ->line('**Valor:** '.number_format($this->quota->amount, 2, ',', '.').' MZN')
            ->line('**Vencimento:** '.$this->quota->due_date->format('d/m/Y'))
            ->line('**Faltam:** '.$daysUntilDue.' dia(s)')
            ->action('Pagar Agora', route('member.quotas.show', $this->quota))
            ->line('Evite multas pagando antes do vencimento.');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Lembrete de Quota',
            'message' => 'Sua quota de '.$this->quota->period.' vence em '.now()->diffInDays($this->quota->due_date).' dia(s)',
            'type' => 'info',
            'link' => route('member.quotas.show', $this->quota),
        ];
    }
}
