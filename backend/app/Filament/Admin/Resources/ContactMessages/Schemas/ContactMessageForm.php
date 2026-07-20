<?php

namespace App\Filament\Admin\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Message')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email()->required(),
                    TextInput::make('subject')->columnSpanFull(),
                    Textarea::make('message')->required()->rows(8)->columnSpanFull(),
                ]),
            ]),
            Section::make('Lifecycle')->collapsed()->schema([
                Grid::make(2)->schema([
                    DateTimePicker::make('read_at')->seconds(false),
                    DateTimePicker::make('replied_at')->seconds(false),
                    TextInput::make('ip')->disabled(),
                    TextInput::make('user_agent')->disabled(),
                ]),
            ]),
        ]);
    }
}
