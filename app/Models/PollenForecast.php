<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollenForecast extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'latitude',
        'longitude',
        'tree_upi',
        'grass_upi',
        'weed_upi',
        'pollen_data',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'date' => 'date',
            'latitude' => 'decimal:2',
            'longitude' => 'decimal:2',
            'pollen_data' => 'array',
        ];
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }
}
