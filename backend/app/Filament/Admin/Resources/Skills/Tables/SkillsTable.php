<?php

namespace App\Filament\Admin\Resources\Skills\Tables;

use App\Enums\SkillLevel;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SkillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultGroup('category')
            ->groupingSettingsHidden()
            ->columns([
                TextColumn::make('name')->searchable()->weight('semibold')->sortable(),
                TextColumn::make('icon')->fontFamily('mono')->color('gray')->toggleable(),
                TextColumn::make('proficiency')->badge(),
                TextColumn::make('years_experience')->numeric()->label('Years')->suffix('y')->sortable(),
                ColorColumn::make('color'),
            ])
            ->filters([
                SelectFilter::make('proficiency')->options(SkillLevel::class),
                SelectFilter::make('category')->options(fn () => \App\Models\Skill::query()->distinct()->pluck('category', 'category')->all()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
