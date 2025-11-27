<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed notification templates
        DB::table('notification_templates')->insert([
            [
                'name' => 'Inscrição Submetida - Email',
                'type' => 'email',
                'module' => 'registration',
                'event' => 'submitted',
                'subject' => 'Inscrição Submetida - OrMM',
                'body' => 'Caro(a) {member_name},\n\nSua inscrição foi submetida com sucesso.\n\nNúmero do Processo: {process_number}\nData de Submissão: {submission_date}\n\nAcompanhe o status da sua inscrição através do portal.\n\nAtenciosamente,\nOrMM',
                'variables' => json_encode(['member_name', 'process_number', 'submission_date']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inscrição Aprovada - Email',
                'type' => 'email',
                'module' => 'registration',
                'event' => 'approved',
                'subject' => 'Inscrição Aprovada - OrMM',
                'body' => 'Caro(a) {member_name},\n\nSua inscrição foi aprovada!\n\nNúmero do Processo: {process_number}\nData de Aprovação: {approval_date}\n\nVocê pode agora acessar o portal e fazer o download do seu cartão digital.\n\nAtenciosamente,\nOrMM',
                'variables' => json_encode(['member_name', 'process_number', 'approval_date']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inscrição Rejeitada - Email',
                'type' => 'email',
                'module' => 'registration',
                'event' => 'rejected',
                'subject' => 'Inscrição Rejeitada - OrMM',
                'body' => 'Caro(a) {member_name},\n\nSua inscrição foi rejeitada.\n\nNúmero do Processo: {process_number}\nMotivo: {rejection_reason}\n\nPor favor, revise os documentos e submeta uma nova inscrição.\n\nAtenciosamente,\nOrMM',
                'variables' => json_encode(['member_name', 'process_number', 'rejection_reason']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lembrete de Pagamento - SMS',
                'type' => 'sms',
                'module' => 'payment',
                'event' => 'reminder',
                'subject' => null,
                'body' => 'OrMM: Lembrete de pagamento. Valor: {amount} MZN. Vencimento: {due_date}. Pague via {payment_method}.',
                'variables' => json_encode(['amount', 'due_date', 'payment_method']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
