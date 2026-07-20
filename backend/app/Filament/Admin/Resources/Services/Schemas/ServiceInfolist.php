<?php

namespace App\Filament\Admin\Resources\Services\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ServiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('summary'),
                TextEntry::make('body')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('icon')
                    ->placeholder('-'),
                TextEntry::make('tier')
                    ->badge(),
                TextEntry::make('starting_price')
                    ->placeholder('-'),
                TextEntry::make('features')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sort')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
