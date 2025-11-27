<?php

namespace App\Notifications\Exam;

use App\Models\ExamApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ExamApplication $application
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Candidatura a Exame Submetida')
            ->line('Sua candidatura ao exame "'.$this->application->exam->name.'" foi submetida com sucesso.')
            ->line('Status: '.ucfirst($this->application->status))
            ->action('Ver Candidatura', route('member.exams.show', $this->application))
            ->line('Obrigado por usar nossa plataforma!');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Candidatura ao exame "'.$this->application->exam->name.'" submetida com sucesso.',
            'application_id' => $this->application->id,
            'exam_id' => $this->application->exam_id,
            'status' => $this->application->status,
        ];
    }
}
