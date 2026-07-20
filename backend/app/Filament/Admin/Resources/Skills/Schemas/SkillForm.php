<?php

namespace App\Filament\Admin\Resources\Skills\Schemas;

use App\Enums\SkillLevel;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->required()->maxLength(80),
                    TextInput::make('category')->required()->datalist(['Languages','Frameworks','Styling','Data','Cloud','Tools'])->helperText('Skills with the same category are grouped together.'),
                    TextInput::make('icon')->placeholder('logos:vue')->helperText('Iconify icon name. Browse at icones.js.org'),
                    Select::make('proficiency')->options(SkillLevel::class)->required()->native(false)->default(SkillLevel::Advanced),
                    TextInput::make('years_experience')->numeric()->default(1)->minValue(0)->maxValue(40),
                    ColorPicker::make('color')->default('#7C5CFF'),
                    TextInput::make('sort')->numeric()->default(0)->columnSpanFull(),
                ]),
            ]),
        ]);
    }
}
