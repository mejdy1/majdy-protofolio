<?php

namespace App\Filament\Admin\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('unread')
                    ->label('')
                    ->getStateUsing(fn (ContactMessage $r) => $r->read_at === null)
                    ->trueIcon('heroicon-s-bell-alert')
                    ->falseIcon('heroicon-o-envelope-open')
                    ->color(fn ($state) => $state ? 'primary' : 'gray'),
                TextColumn::make('name')->searchable()->weight('semibold')
                    ->description(fn (ContactMessage $r) => $r->email),
                TextColumn::make('subject')->searchable()->limit(50)->placeholder('—'),
                TextColumn::make('created_at')->since()->sortable()->label('Received'),
                TextColumn::make('replied_at')->since()->sortable()->placeholder('—'),
            ])
            ->filters([
                TernaryFilter::make('unread')
                    ->placeholder('All')
                    ->trueLabel('Unread only')
                    ->falseLabel('Read only')
                    ->queries(
                        true: fn ($q) => $q->whereNull('read_at'),
                        false: fn ($q) => $q->whereNotNull('read_at'),
                        blank: fn ($q) => $q,
                    ),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->after(fn (ContactMessage $r) => $r->markRead()),
                Action::make('markRead')
                    ->label('Mark read')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (ContactMessage $r) => $r->read_at === null)
                    ->action(fn (ContactMessage $r) => $r->markRead()),
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
