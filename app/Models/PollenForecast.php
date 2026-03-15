<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;

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
        'health_recommendations',
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

    public static function getForLatAndLong(string $date, float $latitude, float $longitude): self
    {
        // Get all forecasts where lat and long are within 0.01 degrees, date is today, ordered by proximity
        $forecast = self::where('latitude', '>=', $latitude - 0.01)
            ->where('latitude', '<=', $latitude + 0.01)
            ->where('longitude', '>=', $longitude - 0.01)
            ->where('longitude', '<=', $longitude + 0.01)
            ->where('date', $date)
            ->orderByRaw("SQRT(POW(latitude - ?, 2) + POW(longitude - ?, 2))", [$latitude, $longitude])
            ->first();

        if (!$forecast) {
            $forecast = self::getFromApi($latitude, $longitude);
        }

        return $forecast;
    }

    public static function getFromApi(float $latitude, float $longitude): self
    {
        $pollenApiResponse = Http::get('https://pollen.googleapis.com/v1/forecast:lookup', [
            "location.longitude" => $longitude,
            "location.latitude" => $latitude,
            "key" => config('services.pollen_api.key'),
            "days" => 1,
        ])->json();

        $upis = [];
        foreach ($pollenApiResponse['dailyInfo'][0]['pollenTypeInfo'] as $pollenTypeInfo) {
            if (!isset($pollenTypeInfo['indexInfo']['value'])) {
                continue;
            }
            if ($pollenTypeInfo['code'] === 'GRASS') {
                $upis['grass']["value"] = $pollenTypeInfo['indexInfo']['value'];
                $upis['grass']["healthRecommendation"] = $pollenTypeInfo['healthRecommendations'][0] ?? null;
            } elseif ($pollenTypeInfo['code'] === 'TREE') {
                $upis['tree']["value"] = $pollenTypeInfo['indexInfo']['value'];
                    $upis['tree']["healthRecommendation"] = $pollenTypeInfo['healthRecommendations'][0] ?? null;
            } elseif ($pollenTypeInfo['code'] === 'WEED') {
                $upis['weed']["value"] = $pollenTypeInfo['indexInfo']['value'];
                $upis['weed']["healthRecommendation"] = $pollenTypeInfo['healthRecommendations'][0] ?? null;
            }
        }

        // Identify the highest UPI and corresponding health recommendation
        $highestUpi = 0;
        $healthRecommendation = null;
        foreach ($upis as $type => $data) {
            if ($data['value'] > $highestUpi) {
                $highestUpi = $data['value'];
                $healthRecommendation = $data['healthRecommendation'] ?? null;
            }
        }

        $forecast = self::create([
            'date' => now()->toDateString(),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'tree_upi' => $upis['tree']['value'] ?? null,
            'grass_upi' => $upis['grass']['value'] ?? null,
            'weed_upi' => $upis['weed']['value'] ?? null,
            'pollen_data' => $pollenApiResponse,
            'health_recommendations' => $healthRecommendation,
        ]);

        return $forecast;
    }
}
