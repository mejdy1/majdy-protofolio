<?php

namespace App\Filament\Admin\Resources\Skills\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SkillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('icon')
                    ->placeholder('-'),
                TextEntry::make('category'),
                TextEntry::make('proficiency')
                    ->badge(),
                TextEntry::make('years_experience')
                    ->numeric(),
                TextEntry::make('color'),
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
