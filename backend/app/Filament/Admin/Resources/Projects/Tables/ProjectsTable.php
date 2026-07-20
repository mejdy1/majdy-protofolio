<?php

namespace App\Filament\Admin\Resources\Projects\Tables;

use App\Enums\ProjectStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort')
            ->defaultSort('sort')
            ->columns([
                ImageColumn::make('cover_url')
                    ->label('')
                    ->circular(false)
                    ->size(56),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn ($record) => str($record->summary)->limit(60)),
                TextColumn::make('client')->searchable()->toggleable(),
                TextColumn::make('year')->sortable()->badge()->color('gray'),
                IconColumn::make('featured')->boolean()->label('★'),
                TextColumn::make('status')->badge(),
                TextColumn::make('updated_at')->since()->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(ProjectStatus::class),
                TernaryFilter::make('featured'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
