<?php

namespace App\Filament\Admin\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('author')
                    ->required(),
                TextInput::make('role'),
                TextInput::make('company'),
                TextInput::make('avatar_url')
                    ->url(),
                FileUpload::make('letter_path')
                    ->label('Recommendation letter (PDF)')
                    ->disk('public')
                    ->directory('letters')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),
                Textarea::make('quote')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(5),
                Toggle::make('featured')
                    ->required(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
