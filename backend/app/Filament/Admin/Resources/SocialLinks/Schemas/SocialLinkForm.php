<?php

namespace App\Filament\Admin\Resources\SocialLinks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SocialLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('platform')
                    ->required(),
                TextInput::make('url')
                    ->url()
                    ->required(),
                TextInput::make('handle'),
                TextInput::make('icon'),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
