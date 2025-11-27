<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Registration\Models\Registration;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'civility',
        'first_name',
        'middle_name',
        'last_name',
        'name',
        'father_name',
        'mother_name',
        'gender_id',
        'birth_country_id',
        'birth_province_id',
        'birth_district_id',
        'birth_date',
        'marital_status_id',
        'identity_document_id',
        'identity_document_number',
        'nationality_id',
        'identity_document_issue_date',
        'identity_document_issue_place',
        'identity_document_expiry_date',
        'has_disability',
        'disability_description',
        'phone',
        'email',
        'mobile',
        'fax',
        'living_address',
        'province',
        'profile_picture_url',
        'note',
        'website',
        'linkedin',
        // Additional fields for registration forms
        'birth_place',
        'marital_status',
        'city_district',
        'neighborhood',
        'full_address',
        'tax_number',
        // 'document_type', // Field doesn't exist in database - use identity_document_id instead
        // 'nationality', // Field doesn't exist in database - use nationality_id instead
        // 'current_residence', // Field doesn't exist in database - use living_address instead
        'university',
        'graduation_date',
        'country_of_formation',
        'years_of_experience',
        'specialty',
        'current_institution',
        // Academic data - Licenciatura
        'degree_type',
        'university_start_year',
        'university_end_year',
        'university_country_id',
        'university_city_district',
        'university_final_grade',
        // Academic data - Ensino Médio
        'high_school_institution',
        'high_school_country_id',
        'high_school_city_district',
        'high_school_completion_year',
        'high_school_final_grade',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'identity_document_issue_date' => 'date',
        'identity_document_expiry_date' => 'date',
        'graduation_date' => 'date',
        'has_disability' => 'boolean',
        'university_final_grade' => 'decimal:2',
        'high_school_final_grade' => 'decimal:2',
    ];

    /**
     * Get the user that owns the person.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the member associated with the person.
     */
    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    /**
     * Get the registrations for the person.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the documents for the person.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the payments for the person.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Current work experience linked via people.current_work_experience_id
     */
    public function currentWorkExperience(): BelongsTo
    {
        return $this->belongsTo(WorkExperience::class, 'current_work_experience_id');
    }

    /**
     * Current academic qualification linked via people.current_academic_qualification_id
     */
    public function currentAcademicQualification(): BelongsTo
    {
        return $this->belongsTo(AcademicQualification::class, 'current_academic_qualification_id');
    }

    /**
     * Get the gender of the person.
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    /**
     * Get the marital status of the person.
     */
    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(CivilState::class, 'marital_status_id');
    }

    /**
     * Get the birth country of the person.
     */
    public function birthCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'birth_country_id');
    }

    /**
     * Get the birth province of the person.
     */
    public function birthProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'birth_province_id');
    }

    /**
     * Get the birth district of the person.
     */
    public function birthDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'birth_district_id');
    }

    /**
     * Get the identity document type of the person.
     */
    public function identityDocument(): BelongsTo
    {
        return $this->belongsTo(IdentityDocument::class, 'identity_document_id');
    }

    /**
     * Get the nationality of the person.
     */
    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }

    /**
     * Get the living country of the person.
     */
    public function livingCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'living_country_id');
    }

    /**
     * Get the living province of the person.
     */
    public function livingProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'living_province_id');
    }

    /**
     * Get the living district of the person.
     */
    public function livingDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'living_district_id');
    }

    /**
     * Get the living neighborhood of the person.
     */
    public function livingNeighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class, 'living_neighborhood_id');
    }

    /**
     * Get the university country of the person.
     */
    public function universityCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'university_country_id');
    }

    /**
     * Get the high school country of the person.
     */
    public function highSchoolCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'high_school_country_id');
    }

    /**
     * Get the work experiences for the person.
     */
    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    /**
     * Get the academic qualifications for the person.
     */
    public function academicQualifications(): HasMany
    {
        return $this->hasMany(AcademicQualification::class);
    }

    /**
     * Get the full name of the person.
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);

        return implode(' ', $parts);
    }
}
