<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentExpiredNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Document $document
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $expiryDate = $this->document->expiry_date?->format('d/m/Y') ?? 'N/A';
        $documentType = $this->document->documentType?->name ?? 'Documento';
        $personName = $this->document->registration?->person?->full_name
            ?? $this->document->member?->person?->full_name
            ?? $this->document->person?->full_name
            ?? 'Usuário';

        return (new MailMessage)
            ->subject('Documento expirado')
            ->greeting('Olá, '.$personName)
            ->line("O documento '{$documentType}' expirou.")
            ->line("Data de expiração: {$expiryDate}")
            ->when(
                $this->document->registration,
                fn (MailMessage $mail) => $mail->action(
                    'Ver Processo de Inscrição',
                    url('/admin/registrations/'.$this->document->registration->id)
                )
            )
            ->line('Por favor, submeta um novo documento para continuar o processo.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Documento expirado',
            'message' => "O documento '{$this->document->documentType?->name}' expirou.",
            'document_id' => $this->document->id,
            'document_type' => $this->document->documentType?->name,
            'expiry_date' => $this->document->expiry_date?->format('Y-m-d'),
            'link' => $this->document->registration
                ? route('admin.registrations.show', $this->document->registration)
                : null,
            'icon' => 'fa-exclamation-circle',
        ];
    }
}
