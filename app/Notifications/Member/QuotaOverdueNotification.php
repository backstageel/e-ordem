<?php

namespace App\Notifications\Member;

use App\Models\Member;
use App\Models\MemberQuota;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotaOverdueNotification extends Notification
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
        $daysOverdue = now()->diffInDays($this->quota->due_date);
        $totalAmount = $this->quota->amount + $this->quota->penalty_amount;

        return (new MailMessage)
            ->subject('Quota em Atraso - ORMM')
            ->greeting('Olá, '.$notifiable->name)
            ->line('⚠️ Sua quota mensal está em atraso.')
            ->line('**Período:** '.$this->quota->period)
            ->line('**Valor:** '.number_format($this->quota->amount, 2, ',', '.').' MZN')
            ->line('**Multa:** '.number_format($this->quota->penalty_amount, 2, ',', '.').' MZN')
            ->line('**Total:** '.number_format($totalAmount, 2, ',', '.').' MZN')
            ->line('**Dias em atraso:** '.$daysOverdue)
            ->action('Pagar Agora', route('member.quotas.show', $this->quota))
            ->line('Por favor, regularize sua situação o mais breve possível para evitar a suspensão da sua conta.');
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Quota em Atraso',
            'message' => 'Sua quota de '.$this->quota->period.' está em atraso há '.now()->diffInDays($this->quota->due_date).' dia(s)',
            'type' => 'warning',
            'link' => route('member.quotas.show', $this->quota),
        ];
    }
}
