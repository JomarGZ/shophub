<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Section::make('Customer Information')
                                    ->icon('heroicon-o-user-circle')
                                    ->description('Customer contact details')
                                    ->schema([
                                        TextEntry::make('shipping_full_name')
                                            ->label('Customer Name')
                                            ->icon('heroicon-m-user')
                                            ->weight('bold'),
                                        TextEntry::make('shipping_phone')
                                            ->label('Contact Number')
                                            ->icon('heroicon-m-phone')
                                            ->copyable(),
                                        TextEntry::make('payment_method')
                                            ->label('Payment Method')
                                            ->icon('heroicon-m-credit-card')
                                            ->badge(),
                                    ]),

                                Section::make('Order Status')
                                    ->icon('heroicon-o-shopping-bag')
                                    ->description('Order tracking information')
                                    ->schema([
                                        TextEntry::make('status')
                                            ->label('Current Status')
                                            ->badge()
                                            ->size('lg')
                                            ->color(fn ($record) => $record->status->color())
                                            ->getStateUsing(fn ($record) => $record->status->label()),
                                        TextEntry::make('created_at')
                                            ->label('Order Date')
                                            ->icon('heroicon-m-calendar')
                                            ->dateTime('F j, Y \a\t g:i A')
                                            ->placeholder('-'),
                                        TextEntry::make('rejection_reason')
                                            ->label('Rejection Reason')
                                            ->icon('heroicon-m-exclamation-circle')
                                            ->placeholder('-')
                                            ->visible(fn ($record) => $record->rejection_reason !== null)
                                            ->color('danger'),
                                    ]),
                            ]),
                    ])
                    ->columns(1),
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Section::make('Shipping Address')
                                    ->icon('heroicon-o-map-pin')
                                    ->description('Delivery destination')
                                    ->schema([
                                        TextEntry::make('shipping_street_address')
                                            ->label('Street Address')
                                            ->icon('heroicon-m-home'),
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('shipping_city')
                                                    ->label('City')
                                                    ->icon('heroicon-m-building-office-2'),
                                                TextEntry::make('shipping_country')
                                                    ->label('Country')
                                                    ->icon('heroicon-m-globe-alt'),
                                            ]),
                                    ]),

                                Section::make('Financial Summary')
                                    ->icon('heroicon-o-currency-dollar')
                                    ->description('Order pricing breakdown')
                                    ->schema([
                                        TextEntry::make('subtotal')
                                            ->label('Subtotal')
                                            ->money('USD')
                                            ->color('gray'),
                                        TextEntry::make('shipping_fee')
                                            ->label('Shipping Fee')
                                            ->money('USD')
                                            ->color('gray'),
                                        TextEntry::make('discount')
                                            ->label('Discount')
                                            ->money('USD')
                                            ->color('warning')
                                            ->visible(fn ($record) => $record->discount > 0),
                                        TextEntry::make('total')
                                            ->label('Total Amount')
                                            ->money('USD')
                                            ->weight('bold')
                                            ->size('lg')
                                            ->color('success'),
                                    ]),
                            ]),
                    ])
                    ->columns(1),
                Section::make('Order Items')
                    ->icon('heroicon-o-shopping-cart')
                    ->description('Products included in this order')
                    ->schema([
                        RepeatableEntry::make('orderItems')
                            ->label('')
                            ->schema([
                                TextEntry::make('product_name')
                                    ->label('Product')
                                    ->weight('semibold')
                                    ->icon('heroicon-m-cube'),
                                TextEntry::make('product_price')
                                    ->label('Unit Price')
                                    ->money('USD')
                                    ->color('gray'),
                                TextEntry::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('line_total')
                                    ->label('Line Total')
                                    ->money('USD')
                                    ->weight('bold')
                                    ->color('success'),
                            ])
                            ->columns(4),
                    ])
                    ->collapsible(),

            ]);
    }
}
