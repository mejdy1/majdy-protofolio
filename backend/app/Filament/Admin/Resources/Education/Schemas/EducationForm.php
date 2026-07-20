<?php

namespace App\Filament\Admin\Resources\Education\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EducationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('school')
                    ->required(),
                TextInput::make('degree')
                    ->required(),
                TextInput::make('field'),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
