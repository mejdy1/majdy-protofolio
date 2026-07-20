<?php

namespace App\Filament\Admin\Resources\SocialLinks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SocialLinkInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('platform'),
                TextEntry::make('url'),
                TextEntry::make('handle')
                    ->placeholder('-'),
                TextEntry::make('icon')
                    ->placeholder('-'),
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
