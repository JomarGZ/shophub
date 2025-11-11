<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->components([
                        TextEntry::make('shipping_full_name')
                            ->label('Customer Name'),
                        TextEntry::make('shipping_phone')
                            ->label('Contact Number'),
                        TextEntry::make('payment_method')
                            ->label('Payment Method'),
                    ]),
                Section::make('Order Details')
                    ->components([
                        TextEntry::make('status')
                            ->label('Order Status')
                            ->badge()
                            ->color(fn ($record) => $record->status->color())
                            ->getStateUsing(fn ($record) => $record->status->label()),
                        TextEntry::make('created_at')
                            ->label('Date Ordered')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->placeholder('-'),
                    ]),
                Section::make('Financial Summary')
                    ->components([
                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->numeric(),
                        TextEntry::make('shipping_fee')
                            ->label('Shipping Fee')
                            ->numeric(),
                        TextEntry::make('discount')
                            ->label('Discount')
                            ->numeric(),
                        TextEntry::make('total')
                            ->label('Total Amount')
                            ->numeric(),
                    ]),
                Section::make('Shipping Address')
                    ->components([
                        TextEntry::make('shipping_country')
                            ->label('Country'),
                        TextEntry::make('shipping_city')
                            ->label('City'),
                        TextEntry::make('shipping_street_address')
                            ->label('Street Address'),
                    ]),
            ]);
    }
}
