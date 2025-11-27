<?php

namespace App\Notifications;

use Modules\Registration\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewRegistrationCreated extends Notification
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
            ->subject('Nova inscrição submetida: '.$reg->registration_number)
            ->greeting('Olá Admin')
            ->line('Foi criada uma nova inscrição.')
            ->line('Candidato: '.($reg->person->full_name ?? '-'))
            ->line('Tipo: '.($reg->registrationType->name ?? '-'))
            ->line('Número: '.$reg->registration_number)
            ->action('Ver Inscrição', route('admin.registrations.show', $reg))
            ->line('Esta é uma notificação automática.');
    }
}


