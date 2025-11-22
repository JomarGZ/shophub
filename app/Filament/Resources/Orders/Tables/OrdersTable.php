<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_full_name')
                    ->label('Customer Name')
                    ->searchable(),
                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Total Amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn ($record) => $record->payment_status?->color() ?? 'muted')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($record) => $record->status?->color() ?? 'muted')
                    ->getStateUsing(fn ($record) => $record->status?->label() ?? 'Unknown')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Date Ordered')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
