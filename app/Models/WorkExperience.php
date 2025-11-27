<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Registration\Models\Registration;

class WorkExperience extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'person_id',
        'institution_id',
        'institution_name',
        'position',
        'department',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'responsibilities',
        'supervisor_name',
        'supervisor_phone',
        'supervisor_email',
        'reason_for_leaving',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the person that owns the work experience.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the institution that owns the work experience.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(WorkInstitution::class, 'institution_id');
    }

    /**
     * Get the registrations that reference this work experience.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'current_work_experience_id');
    }

    /**
     * Scope a query to only include current work experiences.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}
