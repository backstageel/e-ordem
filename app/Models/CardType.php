<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardType extends Model
{
    protected $table = 'card_types';

    protected $fillable = [
        'name',
        'description',
        'color_code',
        'validity_period_days',
        'fee',
        'is_active',
    ];

    protected $casts = [
        'validity_period_days' => 'integer',
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
