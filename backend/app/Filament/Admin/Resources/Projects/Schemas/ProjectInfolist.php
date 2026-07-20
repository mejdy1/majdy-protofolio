<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use App\Models\Project;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
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
                TextEntry::make('cover_url')
                    ->placeholder('-'),
                TextEntry::make('gallery')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('stack')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('live_url')
                    ->placeholder('-'),
                TextEntry::make('repo_url')
                    ->placeholder('-'),
                TextEntry::make('client')
                    ->placeholder('-'),
                TextEntry::make('role')
                    ->placeholder('-'),
                TextEntry::make('year')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('featured')
                    ->boolean(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('sort')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Project $record): bool => $record->trashed()),
            ]);
    }
}
