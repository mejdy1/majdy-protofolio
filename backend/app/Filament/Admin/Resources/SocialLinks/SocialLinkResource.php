<?php

namespace App\Filament\Admin\Resources\SocialLinks;

use App\Filament\Admin\Resources\SocialLinks\Pages\CreateSocialLink;
use App\Filament\Admin\Resources\SocialLinks\Pages\EditSocialLink;
use App\Filament\Admin\Resources\SocialLinks\Pages\ListSocialLinks;
use App\Filament\Admin\Resources\SocialLinks\Pages\ViewSocialLink;
use App\Filament\Admin\Resources\SocialLinks\Schemas\SocialLinkForm;
use App\Filament\Admin\Resources\SocialLinks\Schemas\SocialLinkInfolist;
use App\Filament\Admin\Resources\SocialLinks\Tables\SocialLinksTable;
use App\Models\SocialLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'platform';

    public static function form(Schema $schema): Schema
    {
        return SocialLinkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SocialLinkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SocialLinksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSocialLinks::route('/'),
            'create' => CreateSocialLink::route('/create'),
            'view' => ViewSocialLink::route('/{record}'),
            'edit' => EditSocialLink::route('/{record}/edit'),
        ];
    }
}
