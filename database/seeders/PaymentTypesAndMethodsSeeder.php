<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypesAndMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert payment types (aligned with docs/quotas.md)
        DB::table('payment_types')->insert([
            [
                'code' => 'exam_application',
                'name' => 'Taxa de candidatura ao Exame de Certificação e Residência Médica',
                'description' => 'Candidatura ao exame de certificação e residência médica',
                'default_amount' => 1000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'enrollment_fee',
                'name' => 'Taxa de inscrição (Joia)',
                'description' => 'Joia de inscrição',
                'default_amount' => 3000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'annual_quota',
                'name' => 'Quota anual',
                'description' => 'Quota anual de membro (2020–2025: 4.000 MT; ver histórico no regulamento)',
                'default_amount' => 4000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'quota_late_penalty',
                'name' => 'Multa por atraso no pagamento da quota',
                'description' => '50% do valor da quota (calculado sobre a quota anual vigente)',
                'default_amount' => 0.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'declaration',
                'name' => 'Declaração',
                'description' => 'Emissão de declaração',
                'default_amount' => 500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'specialty_certificate',
                'name' => 'Certificado de Especialidade',
                'description' => 'Emissão de certificado de especialidade',
                'default_amount' => 500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'good_standing_certificate',
                'name' => 'Certificado de Cumprimento (Good Standing)',
                'description' => 'Emissão de certificado de cumprimento (Good Standing)',
                'default_amount' => 500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'card_issue_initial',
                'name' => 'Cartão emitido no ato da inscrição',
                'description' => 'Emissão de cartão no ato da inscrição',
                'default_amount' => 300.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'card_renewal',
                'name' => 'Renovação do Cartão',
                'description' => 'Renovação do cartão de membro',
                'default_amount' => 500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'professional_id_card',
                'name' => 'Carteira Profissional',
                'description' => 'Emissão de carteira profissional',
                'default_amount' => 500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'specialty_minutes_transcription',
                'name' => 'Transcrição da Ata de Especialidade',
                'description' => 'Transcrição da Ata de Especialidade',
                'default_amount' => 500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'processing_fee_provisional_foreign',
                'name' => 'Taxa de tramitação de processos (provisória e certificação estrangeiro)',
                'description' => 'Tramitação de processos para inscrição provisória e certificação de títulos estrangeiros',
                'default_amount' => 2500.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'provisional_authorization_3m',
                'name' => 'Taxa de autorização provisória (até 3 meses)',
                'description' => 'Autorização provisória até 3 meses',
                'default_amount' => 10000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'provisional_authorization_6m',
                'name' => 'Taxa de autorização provisória (até 6 meses)',
                'description' => 'Autorização provisória até 6 meses',
                'default_amount' => 20000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'supervision_daily',
                'name' => 'Taxa de supervisão (por dia)',
                'description' => 'Taxa diária de supervisão',
                'default_amount' => 6000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'provisional_authorization_other',
                'name' => 'Taxa de autorização provisória (outros)',
                'description' => 'Autorização provisória para casos não especificados',
                'default_amount' => 1.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert payment methods
        DB::table('payment_methods')->insert([
            [
                'name' => 'M-Pesa',
                'description' => 'Pagamento via carteira móvel Vodacom',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'E-Mola',
                'description' => 'Pagamento via carteira móvel Movitel',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transferência Bancária',
                'description' => 'Pagamento via transferência bancária',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dinheiro',
                'description' => 'Pagamento em dinheiro',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cartão de Crédito/Débito',
                'description' => 'Pagamento via cartão de crédito ou débito',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cheque',
                'description' => 'Pagamento via cheque bancário',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
