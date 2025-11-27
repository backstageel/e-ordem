<?php

namespace Modules\Registration\Services;

use App\Enums\RegistrationSubtype;
use Illuminate\Support\Facades\DB;
use Modules\Registration\Models\RegistrationType;

class FeeCalculationService
{
    /**
     * Calculate fees for a registration type.
     *
     * @return array{fees: array, total: float, breakdown: array}
     */
    public function calculateForType(RegistrationType $type): array
    {
        // Certification types are identified by code starting with "CERT-"
        if ($type->isCertification()) {
            return $this->calculateForCertification(
                $type->category_number ?? 0
            );
        }

        $category = $type->category;

        return match ($category) {
            \App\Enums\RegistrationCategory::PROVISIONAL => $this->calculateForProvisional(
                $type->subtype_number ?? 0
            ),
            \App\Enums\RegistrationCategory::EFFECTIVE => $this->calculateForEffective(
                $type->grade ?? ''
            ),
            default => [
                'fees' => [],
                'total' => 0.00,
                'breakdown' => [],
            ],
        };
    }

    /**
     * Calculate fees for certification registration.
     * Returns only fees required before submission (exam fee + tramitation if category 2).
     * Fees after approval (joia, quota, cartão) are calculated separately.
     *
     * @param  int  $category  Category number (1, 2, or 3)
     * @param  bool  $includeAfterApproval  Whether to include fees after approval
     * @return array{fees: array, total: float, breakdown: array}
     */
    public function calculateForCertification(int $category, bool $includeAfterApproval = false): array
    {
        $fees = [];
        $breakdown = [];

        // All categories require exam registration fee
        $examFee = $this->getFee('taxa_inscricao_exame');
        $fees[] = [
            'code' => 'taxa_inscricao_exame',
            'name' => 'Taxa de Inscrição no Exame',
            'amount' => $examFee,
            'required' => true,
            'when' => 'before_submission',
        ];
        $breakdown[] = "Taxa de inscrição no exame: {$examFee} MT";

        // Category 2 requires tramitation fee
        $tramitationFee = 0.00;
        if ($category === 2) {
            $tramitationFee = $this->getFee('certificacao_titulos_estrangeiros');
            $fees[] = [
                'code' => 'certificacao_titulos_estrangeiros',
                'name' => 'Taxa de Tramitação (Certificação de Títulos Estrangeiros)',
                'amount' => $tramitationFee,
                'required' => true,
                'when' => 'before_submission',
            ];
            $breakdown[] = "Taxa de tramitação: {$tramitationFee} MT";
        }

        // Calculate initial total (before submission)
        $beforeTotal = $examFee + $tramitationFee;

        // After exam approval (all categories) - only if requested
        $afterTotal = 0.00;
        if ($includeAfterApproval) {
            $joia = $this->getFee('joia');
            $quota = $this->getFee('quota_anual');
            $cartao = $this->getFee('cartao_ato_inscricao');

            $fees[] = [
                'code' => 'joia',
                'name' => 'Jóia',
                'amount' => $joia,
                'required' => true,
                'when' => 'after_approval',
            ];
            $fees[] = [
                'code' => 'quota_anual',
                'name' => 'Quota Anual',
                'amount' => $quota,
                'required' => true,
                'when' => 'after_approval',
            ];
            $fees[] = [
                'code' => 'cartao_ato_inscricao',
                'name' => 'Cartão no Ato da Inscrição',
                'amount' => $cartao,
                'required' => true,
                'when' => 'after_approval',
            ];

            $breakdown[] = 'Após aprovação no exame:';
            $breakdown[] = "  - Jóia: {$joia} MT";
            $breakdown[] = "  - Quota: {$quota} MT";
            $breakdown[] = "  - Cartão: {$cartao} MT";

            $afterTotal = $joia + $quota + $cartao;
        } else {
            $breakdown[] = '';
            $breakdown[] = 'Nota: Após aprovação no exame, será necessário pagar:';
            $breakdown[] = '  - Jóia: '.number_format($this->getFee('joia'), 2, ',', '.').' MT';
            $breakdown[] = '  - Quota Anual: '.number_format($this->getFee('quota_anual'), 2, ',', '.').' MT';
            $breakdown[] = '  - Cartão: '.number_format($this->getFee('cartao_ato_inscricao'), 2, ',', '.').' MT';
        }

        $total = $beforeTotal + $afterTotal;

        return [
            'fees' => $fees,
            'total' => $total,
            'before_submission' => $beforeTotal,
            'after_approval' => $afterTotal,
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Calculate fees for provisional registration.
     *
     * @param  int  $subtype  Subtype number (1-12)
     * @return array{fees: array, total: float, breakdown: array}
     */
    public function calculateForProvisional(int $subtype): array
    {
        $fees = [];
        $breakdown = [];
        $subtypeEnum = RegistrationSubtype::tryFrom($subtype);

        if (! $subtypeEnum) {
            return [
                'fees' => [],
                'total' => 0.00,
                'breakdown' => [],
            ];
        }

        // All subtypes (except exempt ones) require tramitation fee
        // SUBTIPO 10 e 11 não requerem tramitação (são formados em Moçambique)
        if (! $subtypeEnum->isExemptFromCommonRequirements() && ! in_array($subtype, [10, 11], true)) {
            $tramitationFee = $this->getFee('certificacao_titulos_estrangeiros');
            $fees[] = [
                'code' => 'certificacao_titulos_estrangeiros',
                'name' => 'Taxa de Tramitação',
                'amount' => $tramitationFee,
                'required' => true,
                'when' => 'before_submission',
            ];
            $breakdown[] = "Taxa de tramitação: {$tramitationFee} MT";
        }

        // Determine authorization fee based on subtype (not duration)
        // Conforme docs/requisitos_inscricao.md Seção 2.4
        $authorizationFee = 0.00;
        $authorizationCode = null;

        // Subtipos que usam autorização 0-3 meses (3, 4, 5, 7, 12)
        if (in_array($subtype, [3, 4, 5, 7, 12], true)) {
            $authorizationFee = $this->getFee('autorizacao_provisoria_0_3_meses');
            $authorizationCode = 'autorizacao_provisoria_0_3_meses';
            $fees[] = [
                'code' => $authorizationCode,
                'name' => 'Taxa de Autorização Provisória (0-3 meses)',
                'amount' => $authorizationFee,
                'required' => true,
                'when' => 'after_approval',
            ];
            $breakdown[] = "Autorização provisória (0-3 meses): {$authorizationFee} MT";
        }
        // Subtipos que usam autorização 0-6 meses (6, 8, 9)
        elseif (in_array($subtype, [6, 8, 9], true)) {
            $authorizationFee = $this->getFee('autorizacao_provisoria_0_6_meses');
            $authorizationCode = 'autorizacao_provisoria_0_6_meses';
            $fees[] = [
                'code' => $authorizationCode,
                'name' => 'Taxa de Autorização Provisória (0-6 meses)',
                'amount' => $authorizationFee,
                'required' => true,
                'when' => 'after_approval',
            ];
            $breakdown[] = "Autorização provisória (0-6 meses): {$authorizationFee} MT";
        }
        // Subtipos 1, 2, 10, 11 não usam taxa de autorização provisória

        // Some subtypes require exam fee
        if (in_array($subtype, [1, 2, 6, 9, 10, 11], true)) {
            $examFee = $this->getFee('taxa_inscricao_exame');
            $fees[] = [
                'code' => 'taxa_inscricao_exame',
                'name' => 'Taxa de Inscrição no Exame',
                'amount' => $examFee,
                'required' => true,
                'when' => 'before_submission',
            ];
            $breakdown[] = "Taxa de inscrição no exame: {$examFee} MT";
        }

        // Some subtypes require joia + quota + cartão after approval
        if (in_array($subtype, [1, 2, 6, 9, 10, 11], true)) {
            $joia = $this->getFee('joia');
            $quota = $this->getFee('quota_anual');
            $cartao = $this->getFee('cartao_ato_inscricao');

            $fees[] = [
                'code' => 'joia',
                'name' => 'Jóia',
                'amount' => $joia,
                'required' => true,
                'when' => 'after_approval',
            ];
            $fees[] = [
                'code' => 'quota_anual',
                'name' => 'Quota Anual',
                'amount' => $quota,
                'required' => true,
                'when' => 'after_approval',
            ];
            $fees[] = [
                'code' => 'cartao_ato_inscricao',
                'name' => 'Cartão no Ato da Inscrição',
                'amount' => $cartao,
                'required' => true,
                'when' => 'after_approval',
            ];

            $breakdown[] = 'Após aprovação:';
            $breakdown[] = "  - Jóia: {$joia} MT";
            $breakdown[] = "  - Quota: {$quota} MT";
            $breakdown[] = "  - Cartão: {$cartao} MT";
        }

        // Calculate totals
        // SUBTIPO 10 e 11 não requerem tramitação
        $tramitationAmount = ($subtypeEnum->isExemptFromCommonRequirements() || in_array($subtype, [10, 11], true))
            ? 0
            : $this->getFee('certificacao_titulos_estrangeiros');

        $beforeTotal = $tramitationAmount +
            (in_array($subtype, [1, 2, 6, 9, 10, 11], true) ? $this->getFee('taxa_inscricao_exame') : 0);
        $afterTotal = $authorizationFee +
            (in_array($subtype, [1, 2, 6, 9, 10, 11], true) ? ($this->getFee('joia') + $this->getFee('quota_anual') + $this->getFee('cartao_ato_inscricao')) : 0);
        $total = $beforeTotal + $afterTotal;

        return [
            'fees' => $fees,
            'total' => $total,
            'before_submission' => $beforeTotal,
            'after_approval' => $afterTotal,
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Calculate fees for effective registration.
     *
     * @param  string  $grade  Grade (A, B, or C)
     * @return array{fees: array, total: float, breakdown: array}
     */
    public function calculateForEffective(string $grade): array
    {
        $fees = [];
        $breakdown = [];

        // All effective registrations require joia + quota + cartão
        $joia = $this->getFee('joia');
        $quota = $this->getFee('quota_anual');
        $cartao = $this->getFee('cartao_ato_inscricao');

        $fees[] = [
            'code' => 'joia',
            'name' => 'Jóia',
            'amount' => $joia,
            'required' => true,
            'when' => 'before_submission',
        ];
        $fees[] = [
            'code' => 'quota_anual',
            'name' => 'Quota Anual',
            'amount' => $quota,
            'required' => true,
            'when' => 'before_submission',
        ];
        $fees[] = [
            'code' => 'cartao_ato_inscricao',
            'name' => 'Cartão no Ato da Inscrição',
            'amount' => $cartao,
            'required' => true,
            'when' => 'before_submission',
        ];

        $breakdown[] = "Jóia: {$joia} MT";
        $breakdown[] = "Quota: {$quota} MT";
        $breakdown[] = "Cartão: {$cartao} MT";

        $total = $joia + $quota + $cartao;

        return [
            'fees' => $fees,
            'total' => $total,
            'before_submission' => $total,
            'after_approval' => 0.00,
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Get fee amount by code.
     */
    protected function getFee(string $code): float
    {
        $fee = DB::table('registration_fees')
            ->where('code', $code)
            ->where('is_active', true)
            ->value('amount');

        return (float) ($fee ?? 0.00);
    }
}
