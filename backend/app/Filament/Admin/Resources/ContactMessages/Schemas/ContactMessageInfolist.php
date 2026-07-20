<?php

namespace App\Filament\Admin\Resources\ContactMessages\Schemas;

use App\Models\ContactMessage;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('subject')
                    ->placeholder('-'),
                TextEntry::make('message')
                    ->columnSpanFull(),
                TextEntry::make('ip')
                    ->placeholder('-'),
                TextEntry::make('user_agent')
                    ->placeholder('-'),
                TextEntry::make('read_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('replied_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ContactMessage $record): bool => $record->trashed()),
            ]);
    }
}
