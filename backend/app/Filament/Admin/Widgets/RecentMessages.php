<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ContactMessage;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMessages extends BaseWidget
{
    protected static ?int $sort = -1;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent messages')
            ->query(ContactMessage::query()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('semibold')
                    ->description(fn (ContactMessage $r) => $r->email),
                Tables\Columns\TextColumn::make('subject')->limit(60)->placeholder('—'),
                Tables\Columns\IconColumn::make('unread')
                    ->label('')
                    ->getStateUsing(fn (ContactMessage $r) => $r->read_at === null)
                    ->trueIcon('heroicon-s-bell-alert')
                    ->falseIcon('heroicon-o-envelope-open')
                    ->color(fn ($s) => $s ? 'primary' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->paginated(false);
    }
}
