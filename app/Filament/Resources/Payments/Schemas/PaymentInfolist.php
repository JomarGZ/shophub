<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2) // Display in two columns for better use of space
            ->components([
                
                Section::make('Order Details')
                    ->schema([
                        TextEntry::make('order.id')
                            ->label('Order'),
                        TextEntry::make('order.payment_method')
                            ->label('Payment Method')
                            ->badge(),
                        TextEntry::make('status')
                            ->badge(),
                        TextEntry::make('amount')
                            ->numeric(),
                        TextEntry::make('currency')
                            ->placeholder('-'),
                    ]),

                Section::make('Transaction Info')
                    ->schema([
                        TextEntry::make('transaction_id')
                            ->label('Transaction ID')
                            ->placeholder('-'),
                        TextEntry::make('payment_reference')
                            ->label('Payment Reference')
                            ->placeholder('-'),
                    ]),

                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('paid_at')
                            ->label('Paid At')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columnSpan('full'),
            ]);
    }
}
