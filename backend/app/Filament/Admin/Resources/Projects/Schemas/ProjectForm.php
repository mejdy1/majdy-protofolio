<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use App\Enums\ProjectStatus;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->schema([
                Section::make('Overview')
                    ->description('Cover, headline and short summary that show up in lists.')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(160)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(160)
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from the title — edit only if you must.'),
                        Textarea::make('summary')
                            ->required()
                            ->rows(3)
                            ->maxLength(280)
                            ->helperText('Used in cards and meta description.'),
                        TextInput::make('cover_url')
                            ->label('Cover image URL')
                            ->url()
                            ->prefixIcon('heroicon-o-photo'),
                    ]),
                Section::make('Status')
                    ->columnSpan(1)
                    ->schema([
                        Toggle::make('featured')->inline(false)->helperText('Featured projects appear on the homepage.'),
                        Select::make('status')
                            ->options(ProjectStatus::class)
                            ->default(ProjectStatus::Published)
                            ->required()
                            ->native(false),
                        TextInput::make('year')->numeric()->minValue(1990)->maxValue(2100),
                        TextInput::make('sort')->numeric()->default(0)->helperText('Lower = earlier in the list.'),
                    ]),
            ]),

            Section::make('Details')
                ->columns(2)
                ->schema([
                    TextInput::make('client')->maxLength(120),
                    TextInput::make('role')->maxLength(120),
                    TextInput::make('live_url')->url()->prefixIcon('heroicon-o-globe-alt')->label('Live URL'),
                    TextInput::make('repo_url')->url()->prefixIcon('heroicon-o-code-bracket')->label('Repository URL'),
                    TagsInput::make('stack')->placeholder('Add a tech')->columnSpanFull(),
                    TagsInput::make('gallery')->placeholder('Paste an image URL')->columnSpanFull()->helperText('Each entry should be a full image URL.'),
                ]),

            Section::make('Case study')
                ->description('For featured projects, fill in Problem and Solution — they render as distinct blocks on the homepage.')
                ->schema([
                    RichEditor::make('problem')
                        ->label('Problem statement')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'link', 'bulletList', 'orderedList', 'undo', 'redo',
                        ]),
                    RichEditor::make('solution')
                        ->label('Solution')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'link', 'h3', 'blockquote', 'bulletList', 'orderedList', 'undo', 'redo',
                        ]),
                    RichEditor::make('body')
                        ->label('Full write-up (optional)')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'strike', 'link', 'h2', 'h3', 'blockquote', 'codeBlock', 'bulletList', 'orderedList', 'undo', 'redo',
                        ]),
                ]),
        ]);
    }
}
