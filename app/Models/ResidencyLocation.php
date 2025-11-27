<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResidencyLocation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'residency_locations';

    protected $guarded = false;

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the country for this location.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Get the program locations for this location.
     */
    public function programLocations(): HasMany
    {
        return $this->hasMany(ResidencyProgramLocation::class, 'residency_location_id');
    }

    /**
     * Get the applications for this location.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(ResidencyApplication::class, 'residency_location_id');
    }

    /**
     * Get the location status as a badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->is_active) {
            true => '<span class="badge bg-success">Ativo</span>',
            false => '<span class="badge bg-danger">Inativo</span>',
            default => '<span class="badge bg-secondary">Desconhecido</span>',
        };
    }

    /**
     * Get the nome attribute (Portuguese accessor for name).
     */
    public function getNomeAttribute()
    {
        return $this->name;
    }

    /**
     * Get the endereco attribute (Portuguese accessor for address).
     */
    public function getEnderecoAttribute()
    {
        return $this->address;
    }

    /**
     * Get the cidade attribute (Portuguese accessor for city).
     */
    public function getCidadeAttribute()
    {
        return $this->city;
    }

    /**
     * Get the provincia attribute (Portuguese accessor for province).
     */
    public function getProvinciaAttribute()
    {
        return $this->province;
    }

    /**
     * Get the capacidade attribute (Portuguese accessor for capacity).
     */
    public function getCapacidadeAttribute()
    {
        return $this->capacity;
    }
}
