<?php

namespace App\Filament\Admin\Resources\Certifications\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('title')->required()->maxLength(180),
                    TextInput::make('issuer')->required()->maxLength(160),
                    DatePicker::make('issue_date'),
                    DatePicker::make('expiry_date'),
                    TextInput::make('credential_id')->label('Credential ID')->maxLength(160),
                    TextInput::make('credential_url')->label('Credential URL')->url()->prefixIcon('heroicon-o-link'),
                    Toggle::make('featured')->inline(false)->helperText('Show on the homepage.'),
                    TextInput::make('sort')->numeric()->default(0)->helperText('Lower = earlier.'),
                    FileUpload::make('file_path')
                        ->label('Certificate PDF')
                        ->disk('public')
                        ->directory('certifications')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(10240)
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull(),
                ]),
            ]),
        ]);
    }
}
