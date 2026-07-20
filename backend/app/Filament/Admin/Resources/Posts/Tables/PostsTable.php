<?php

namespace App\Filament\Admin\Resources\Posts\Tables;

use App\Enums\PostStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_url')->label('')->size(56),
                TextColumn::make('title')->searchable()->weight('semibold')->sortable()
                    ->description(fn ($r) => $r->category?->name ?? '—'),
                TextColumn::make('status')->badge(),
                IconColumn::make('featured')->boolean()->label('★'),
                TextColumn::make('reading_time')->suffix(' min'),
                TextColumn::make('published_at')->dateTime('M j, Y')->sortable()->placeholder('—'),
                TextColumn::make('updated_at')->since()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(PostStatus::class),
                TernaryFilter::make('featured'),
                TrashedFilter::make(),
            ])
            ->recordActions([
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
