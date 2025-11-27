<?php

namespace Modules\Registration\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationFeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Base: Anexo B - Tabela Oficial de Taxas OrMM (20/11/2025)
     */
    public function run(): void
    {
        // Categoria 1: Inscrição & Anuidades
        $this->createFee('joia', 'Taxa de Inscrição (Jóia)', 3000.00, 'Categoria 1: Inscrição & Anuidades');
        $this->createFee('quota_anual', 'Quota Anual', 4000.00, 'Categoria 1: Inscrição & Anuidades (Vigente 2020-2025)');
        $this->createFee('multa_atraso_quota', 'Multa por Atraso da Quota', 0.5, 'Categoria 1: Inscrição & Anuidades (0,5 sobre valor da quota em atraso)');

        // Categoria 2: Emissão de Documentos
        $this->createFee('declaracao', 'Declaração', 500.00, 'Categoria 2: Emissão de Documentos');
        $this->createFee('certificado_especialidade', 'Certificado de Especialidade', 500.00, 'Categoria 2: Emissão de Documentos');
        $this->createFee('certificado_regularidade', 'Certificado de Regularidade (Good Standing)', 500.00, 'Categoria 2: Emissão de Documentos');
        $this->createFee('carteira_profissional', 'Carteira Profissional', 500.00, 'Categoria 2: Emissão de Documentos');
        $this->createFee('cartao_ato_inscricao', 'Cartão no Ato da Inscrição', 300.00, 'Categoria 2: Emissão de Documentos');
        $this->createFee('renovacao_cartao', 'Renovação do Cartão', 500.00, 'Categoria 2: Emissão de Documentos');
        $this->createFee('transcricao_ata_especialidade', 'Transcrição da Ata de Especialidade', 500.00, 'Categoria 2: Emissão de Documentos');

        // Categoria 3: Processos Especiais
        $this->createFee('certificacao_titulos_estrangeiros', 'Certificação de Títulos Estrangeiros', 2500.00, 'Categoria 3: Processos Especiais (Inclui inscrição provisória e inscrição para exames no estrangeiro)');
        $this->createFee('autorizacao_provisoria_0_3_meses', 'Taxa de Autorização Provisória (0-3 meses)', 10000.00, 'Categoria 3: Processos Especiais (Validade de 0 a 3 meses)');
        $this->createFee('autorizacao_provisoria_0_6_meses', 'Taxa de Autorização Provisória (0-6 meses)', 20000.00, 'Categoria 3: Processos Especiais (Validade de 0 a 6 meses)');
        $this->createFee('taxa_supervisao', 'Taxa de Supervisão', 6000.00, 'Categoria 3: Processos Especiais (Por dia)');

        // Taxas de Exames
        $this->createFee('taxa_inscricao_exame', 'Taxa de Inscrição no Exame', 1000.00, 'Taxa para inscrição em exames de certificação');
    }

    /**
     * Create a fee entry.
     */
    protected function createFee(string $code, string $name, float $amount, string $description): void
    {
        DB::table('registration_fees')->updateOrInsert(
            ['code' => $code],
            [
                'name' => $name,
                'amount' => $amount,
                'description' => $description,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
