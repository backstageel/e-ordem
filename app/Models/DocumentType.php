<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_required',
        'requires_translation',
        'requires_validation',
        'is_active',
        'order',
        'instructions',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'requires_translation' => 'boolean',
        'requires_validation' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the documents for the document type.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Scope a query to only include required document types.
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope a query to only include active document types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include document types that require translation.
     */
    public function scopeRequiresTranslation($query)
    {
        return $query->where('requires_translation', true);
    }

    /**
     * Scope a query to only include document types that require validation.
     */
    public function scopeRequiresValidation($query)
    {
        return $query->where('requires_validation', true);
    }
}
