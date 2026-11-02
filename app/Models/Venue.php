<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'address',
        'description',
        'phone',
        'opening_time',
        'closing_time',
        'status',
        'allow_full_online',
        'allow_dp',
        'allow_onsite',
        'dp_type',
        'dp_value',
    ];

    protected function casts(): array
    {
        return [
            'allow_full_online' => 'boolean',
            'allow_dp' => 'boolean',
            'allow_onsite' => 'boolean',
            'dp_value' => 'decimal:2',
        ];
    }

    public function courts(): HasMany
    {
        return $this->hasMany(Court::class);
    }
}
