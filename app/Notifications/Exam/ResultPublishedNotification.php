<?php

namespace App\Notifications\Exam;

use App\Models\ExamApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultPublishedNotification extends Notification implements ShouldQueue
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
        $result = $this->application->result;
        $exam = $this->application->exam;

        $message = (new MailMessage)
            ->subject('Resultado do Exame Publicado')
            ->line('O resultado do seu exame "'.$exam->name.'" foi publicado.');

        if ($result) {
            $message->line('Nota: '.$result->grade.' / 20')
                ->line('Status: '.ucfirst($result->status))
                ->line('Decisão: '.ucfirst($result->decision ?? 'Pendente'));

            if ($result->decision === 'aprovado') {
                $message->line('Parabéns! Você foi aprovado no exame.');
            } elseif ($result->decision === 'reprovado') {
                $message->line('Infelizmente, você não alcançou a nota mínima necessária.');
                $message->line('Você pode apresentar recurso em até 10 dias úteis.');
            }
        }

        $message->action('Ver Resultado Completo', route('member.exams.results', $this->application));

        return $message;
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Resultado do exame "'.$this->application->exam->name.'" publicado.',
            'application_id' => $this->application->id,
            'grade' => $this->application->result?->grade,
            'decision' => $this->application->result?->decision,
        ];
    }
}
