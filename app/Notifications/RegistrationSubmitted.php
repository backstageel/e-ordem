<?php

namespace App\Notifications;

use Modules\Registration\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrationSubmitted extends Notification
{
    use Queueable;

    public function __construct(public Registration $registration)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $reg = $this->registration;
        return (new MailMessage)
            ->subject('Inscrição submetida: '.$reg->registration_number)
            ->greeting('Olá, '.$notifiable->full_name)
            ->line('A sua inscrição foi submetida com sucesso.')
            ->line('Número de Inscrição: '.$reg->registration_number)
            ->line('Valor a pagar: '.number_format($reg->registrationType->fee, 2, ',', '.').' MT')
            ->line('Use a referência exibida na página de sucesso para efetuar o pagamento.')
            ->action('Verificar Status', url(route('guest.registrations.check-status')))
            ->line('Obrigado.');
    }
}


