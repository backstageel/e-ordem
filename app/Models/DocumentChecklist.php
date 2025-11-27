<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentChecklist extends Model
{
    protected $table = 'document_checklists';

    protected $fillable = [
        'registration_type_id',
        'document_type_id',
        'is_required',
        'requires_translation',
        'requires_validation',
        'order',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'requires_translation' => 'boolean',
        'requires_validation' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the registration type.
     */
    public function registrationType(): BelongsTo
    {
        return $this->belongsTo(RegistrationType::class);
    }

    /**
     * Get the document type.
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }
}
