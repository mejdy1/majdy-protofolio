<?php

namespace App\Filament\Admin\Resources\Testimonials\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TestimonialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('author'),
                TextEntry::make('role')
                    ->placeholder('-'),
                TextEntry::make('company')
                    ->placeholder('-'),
                TextEntry::make('avatar_url')
                    ->placeholder('-'),
                TextEntry::make('quote')
                    ->columnSpanFull(),
                TextEntry::make('rating')
                    ->numeric(),
                IconEntry::make('featured')
                    ->boolean(),
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
