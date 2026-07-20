<?php

namespace App\Filament\Admin\Resources\Experiences\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('role')->required()->maxLength(120),
                    TextInput::make('company')->required()->maxLength(120),
                    TextInput::make('company_url')->url()->prefixIcon('heroicon-o-globe-alt'),
                    TextInput::make('location')->placeholder('Remote · Riyadh'),
                    DatePicker::make('start_date')->required()->native(false),
                    DatePicker::make('end_date')->native(false)->hint('Leave blank if current'),
                    Toggle::make('current')->inline(false),
                    TextInput::make('sort')->numeric()->default(0),
                    RichEditor::make('description')->columnSpanFull(),
                    TagsInput::make('achievements')->columnSpanFull(),
                    TagsInput::make('stack')->columnSpanFull(),
                ]),
            ]),
        ]);
    }
}
