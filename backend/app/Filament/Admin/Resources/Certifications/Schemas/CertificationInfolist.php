<?php

namespace App\Filament\Admin\Resources\Certifications\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CertificationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('issuer'),
                TextEntry::make('issue_date')->date()->placeholder('-'),
                TextEntry::make('expiry_date')->date()->placeholder('-'),
                TextEntry::make('credential_id')->placeholder('-'),
                TextEntry::make('credential_url')->placeholder('-'),
                TextEntry::make('file_path')->label('Certificate PDF')->placeholder('-'),
                TextEntry::make('sort')->numeric(),
                TextEntry::make('created_at')->dateTime()->placeholder('-'),
                TextEntry::make('updated_at')->dateTime()->placeholder('-'),
            ]);
    }
}
