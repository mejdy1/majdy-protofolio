<?php

namespace App\Filament\Admin\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->schema([
                Section::make('Content')->columnSpan(2)->schema([
                    TextInput::make('title')->required()->maxLength(180)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($s, callable $set) => $set('slug', Str::slug($s))),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Textarea::make('excerpt')->required()->rows(3)->maxLength(280),
                    RichEditor::make('body')->required()->columnSpanFull()->toolbarButtons([
                        'bold','italic','strike','link','h2','h3','blockquote','codeBlock','bulletList','orderedList','undo','redo',
                    ]),
                ]),
                Section::make('Meta')->columnSpan(1)->schema([
                    Select::make('category_id')->relationship('category', 'name')->native(false)->preload(),
                    Select::make('status')->options(PostStatus::class)->default(PostStatus::Draft)->required()->native(false),
                    Toggle::make('featured')->inline(false),
                    DateTimePicker::make('published_at')->seconds(false),
                    TextInput::make('cover_url')->url()->prefixIcon('heroicon-o-photo'),
                    TextInput::make('reading_time')->numeric()->suffix('min')->default(3),
                ]),
            ]),
        ]);
    }
}
