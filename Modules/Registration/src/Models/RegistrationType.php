<?php

namespace Modules\Registration\Models;

use App\Enums\RegistrationCategory;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Registration\Database\Factories\RegistrationTypeFactory;

class RegistrationType extends BaseModel
{
    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return RegistrationTypeFactory::new();
    }
    // Unguarded and auditing handled by BaseModel

    protected $casts = [
        'category' => RegistrationCategory::class,
        'fee' => 'decimal:2',
        'validity_period_days' => 'integer',
        'renewable' => 'boolean',
        'max_renewals' => 'integer',
        'required_documents' => 'array',
        'eligibility_criteria' => 'array',
        'workflow_steps' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the registrations for the registration type.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Scope a query to only include provisional registration types.
     */
    public function scopeProvisional($query)
    {
        return $query->where('category', RegistrationCategory::PROVISIONAL);
    }

    /**
     * Scope a query to only include effective registration types.
     */
    public function scopeEffective($query)
    {
        return $query->where('category', RegistrationCategory::EFFECTIVE);
    }

    /**
     * Scope a query to only include renewal registration types.
     */
    public function scopeRenewal($query)
    {
        return $query->where('name', 'like', 'Renewal%');
    }

    /**
     * Scope a query to only include reinstatement registration types.
     */
    public function scopeReinstatement($query)
    {
        return $query->where('name', 'like', 'Reinstatement%');
    }

    /**
     * Scope a query to only include active registration types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include registration types by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Check if the registration type is renewable.
     */
    public function isRenewable(): bool
    {
        return $this->renewable && $this->max_renewals > 0;
    }

    /**
     * Check if the registration type is provisional.
     */
    public function isProvisional(): bool
    {
        return $this->category === RegistrationCategory::PROVISIONAL;
    }

    /**
     * Check if the registration type is effective.
     */
    public function isEffective(): bool
    {
        return $this->category === RegistrationCategory::EFFECTIVE;
    }

    /**
     * Get the required documents for this registration type.
     */
    public function getRequiredDocuments(): array
    {
        return $this->required_documents ?? [];
    }

    /**
     * Get common documents (13 comuns para provisórias).
     */
    public function getCommonDocuments(): array
    {
        if (! $this->isProvisional()) {
            return [];
        }

        $requiredDocs = $this->required_documents ?? [];

        // Se required_documents é um array com estrutura {common: [...], specific: {...}}
        if (isset($requiredDocs['common']) && is_array($requiredDocs['common'])) {
            return $requiredDocs['common'];
        }

        // Se é um array simples, retorna vazio (sem documentos comuns separados)
        return [];
    }

    /**
     * Get specific documents for this registration type/subtype.
     */
    public function getSpecificDocuments(): array
    {
        $requiredDocs = $this->required_documents ?? [];

        // Se required_documents é um array com estrutura {common: [...], specific: {...}}
        if (isset($requiredDocs['specific'])) {
            if (is_array($requiredDocs['specific'])) {
                // Se specific é um objeto com subtipos
                if ($this->subtype_number && isset($requiredDocs['specific']["subtype_{$this->subtype_number}"])) {
                    return $requiredDocs['specific']["subtype_{$this->subtype_number}"];
                }

                // Se specific é um array simples
                return $requiredDocs['specific'];
            }
        }

        // Se required_documents é um array simples, retorna ele mesmo
        if (is_array($requiredDocs) && ! isset($requiredDocs['common'])) {
            return $requiredDocs;
        }

        return [];
    }

    /**
     * Get all required documents (common + specific).
     */
    public function getAllRequiredDocuments(): array
    {
        $common = $this->getCommonDocuments();
        $specific = $this->getSpecificDocuments();

        return array_merge($common, $specific);
    }

    /**
     * Get the eligibility criteria for this registration type.
     */
    public function getEligibilityCriteria(): array
    {
        $criteria = $this->eligibility_criteria;

        // Ensure it's always an array
        if (is_string($criteria)) {
            $criteria = json_decode($criteria, true) ?? [];
        }

        return is_array($criteria) ? $criteria : [];
    }

    /**
     * Get the workflow steps for this registration type.
     */
    public function getWorkflowSteps(): array
    {
        return $this->workflow_steps ?? [];
    }

    /**
     * Check if a document is required for this registration type.
     */
    public function requiresDocument(string $documentType): bool
    {
        return in_array($documentType, $this->getRequiredDocuments());
    }

    /**
     * Get the label for the category.
     */
    public function getCategoryLabel(): string
    {
        return $this->category->label();
    }

    /**
     * Scope a query to only include certification registration types.
     */
    public function scopeCertification($query)
    {
        return $query->where('code', 'like', 'CERT-%');
    }

    /**
     * Check if the registration type is certification.
     */
    public function isCertification(): bool
    {
        return str_starts_with($this->code, 'CERT-');
    }
}
