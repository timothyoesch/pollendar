<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'pollen_forecast_id',
        'date',
        'symptoms_severity',
        'symptoms',
        'medication_taken',
        'medication_information',
        'notes',
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
            'user_id' => 'integer',
            'pollen_forecast_id' => 'integer',
            'date' => 'date',
            'symptoms' => 'array',
            'medication_taken' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pollenForecast(): BelongsTo
    {
        return $this->belongsTo(PollenForecast::class);
    }
}
