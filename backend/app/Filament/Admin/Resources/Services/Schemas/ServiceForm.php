<?php

namespace App\Filament\Admin\Resources\Services\Schemas;

use App\Enums\ServiceTier;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Service')->schema([
                Grid::make(2)->schema([
                    TextInput::make('title')->required()->maxLength(120)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($s, callable $set) => $set('slug', Str::slug($s))),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Textarea::make('summary')->required()->columnSpanFull()->rows(2),
                    TextInput::make('icon')->placeholder('heroicon-o-sparkles'),
                    Select::make('tier')->options(ServiceTier::class)->default(ServiceTier::Standard)->required()->native(false),
                    TextInput::make('starting_price')->prefixIcon('heroicon-o-currency-dollar'),
                    TextInput::make('sort')->numeric()->default(0),
                    TagsInput::make('features')->columnSpanFull(),
                ]),
            ]),
            Section::make('Long description')->schema([
                RichEditor::make('body')->columnSpanFull(),
            ]),
        ]);
    }
}
