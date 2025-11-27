<?php

namespace App\Notifications\Exam;

use App\Models\ExamApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamScheduledNotification extends Notification implements ShouldQueue
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
        $schedule = $this->application->schedule;
        $exam = $this->application->exam;

        return (new MailMessage)
            ->subject('Exame Agendado com Sucesso')
            ->line('Seu exame "'.$exam->name.'" foi agendado com sucesso.')
            ->line('Data: '.$schedule->date->format('d/m/Y'))
            ->line('Horário: '.$schedule->start_time->format('H:i').' - '.$schedule->end_time->format('H:i'))
            ->line('Local: '.$schedule->location)
            ->line('Chegue 30 minutos antes do início.')
            ->action('Ver Detalhes', route('member.exams.show', $this->application))
            ->line('Boa sorte no seu exame!');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Exame "'.$this->application->exam->name.'" agendado com sucesso.',
            'application_id' => $this->application->id,
            'schedule_id' => $this->application->exam_schedule_id,
        ];
    }
}
