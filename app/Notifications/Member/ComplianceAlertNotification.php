<?php

namespace App\Notifications\Member;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplianceAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Member $member;

    protected array $issues;

    public function __construct(Member $member, array $issues)
    {
        $this->member = $member;
        $this->issues = $issues;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $issuesList = [];

        if (isset($this->issues['expired_documents'])) {
            $count = count($this->issues['expired_documents']);
            $issuesList[] = "• {$count} documento(s) expirado(s)";
        }

        if (isset($this->issues['missing_documents'])) {
            $count = count($this->issues['missing_documents']);
            $issuesList[] = "• {$count} documento(s) em falta";
        }

        if (isset($this->issues['profile_update_required'])) {
            $issuesList[] = '• Perfil precisa de atualização';
        }

        if (isset($this->issues['quota_irregular'])) {
            $details = $this->issues['quota_irregular_details'] ?? [];
            $overdueCount = $details['overdue_count'] ?? 0;
            $totalDue = $details['total_due'] ?? 0;
            $issuesList[] = "• Quotas irregulares: {$overdueCount} quota(s) em atraso, Total: ".number_format($totalDue, 2, ',', '.').' MT';
        }

        $issuesText = implode("\n", $issuesList);

        return (new MailMessage)
            ->subject('Alerta de Conformidade - Ação Necessária')
            ->greeting('Olá '.$this->member->full_name.',')
            ->line('Detectamos algumas questões de conformidade na sua conta que precisam da sua atenção:')
            ->line($issuesText)
            ->action('Ver Detalhes', route('member.dashboard.index'))
            ->line('Por favor, acesse o portal para resolver essas questões o mais breve possível.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'compliance_alert',
            'member_id' => $this->member->id,
            'issues' => $this->issues,
            'message' => 'Você tem questões de conformidade que precisam de atenção.',
        ];
    }
}
