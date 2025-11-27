<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Neighborhood extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'district_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the district that owns the neighborhood.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the people living in this neighborhood.
     */
    public function people(): HasMany
    {
        return $this->hasMany(Person::class, 'living_neighborhood_id');
    }

    /**
     * Scope a query to only include active neighborhoods.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
