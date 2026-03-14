<?php

namespace App\Filament\Resources\Entries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->native(false)
                    ->preload(true)
                    ->required(),
                Select::make('pollen_forecast_id')
                    ->relationship('pollenForecast', 'id')
                    ->native(false)
                    ->preload(true)
                    ->default(null),
                DatePicker::make('date')
                    ->required(),
                Select::make('symptoms_severity')
                    ->required()
                    ->options([
                        0 => 'None',
                        1 => 'Very low',
                        2 => 'Low',
                        3 => 'Moderate',
                        4 => 'High',
                        5 => 'Very high',
                    ])
                    ->default(0),
                Select::make('symptoms')
                    ->options([
                        'runny_nose' => 'Runny Nose',
                        'sneezing' => 'Sneezing',
                        'itchy_eyes' => 'Itchy Eyes',
                        'congestion' => 'Congestion',
                    ])
                    ->multiple()
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('medication_taken')
                    ->default(false)
                    ->required(),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
