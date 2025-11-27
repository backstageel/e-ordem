<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Registration\Models\Registration;

class AcademicQualification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'person_id',
        'institution_id',
        'qualification_type',
        'field_of_study',
        'institution_name',
        'start_date',
        'completion_date',
        'grade',
        'gpa',
        'certificate_number',
        'certificate_path',
        'is_verified',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'completion_date' => 'date',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the person that owns the academic qualification.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the institution that owns the academic qualification.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(AcademicInstitution::class, 'institution_id');
    }

    /**
     * Get the registrations that reference this academic qualification.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'current_academic_qualification_id');
    }

    /**
     * Scope a query to only include verified qualifications.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
