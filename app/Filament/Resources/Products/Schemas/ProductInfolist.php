<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3) // Divide layout into 3 columns for better structuring
            ->components([
                TextEntry::make('category.name')
                    ->label('Category')
                    ->columnSpan(1)
                    ->numeric(),

                TextEntry::make('price')
                    ->money('USD') // Specify currency for clarity, modify as needed
                    ->columnSpan(1),

                TextEntry::make('stock')
                    ->numeric()
                    ->columnSpan(1),

                TextEntry::make('name')
                    ->columnSpanFull() // Full width for product name to stand out
                    ->weight(FontWeight::Bold),

                TextEntry::make('description')
                    ->columnSpanFull()
                    ->placeholder('-'),

                ImageEntry::make('image_url')
                    ->label('Product Image')
                    ->disk('public')
                    ->columnSpan(1)
                    ->imageHeight('auto')
                    ->imageWidth(200)
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->label('Created At')
                    ->columnSpan(1)
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->label('Updated At')
                    ->columnSpan(1)
                    ->placeholder('-'),
            ]);
    }
}
