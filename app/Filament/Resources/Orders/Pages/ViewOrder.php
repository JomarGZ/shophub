<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('change_status')
                ->label('Change Status')
                ->icon('heroicon-m-arrows-right-left')
                ->schema([
                    Select::make('status')
                        ->options(OrderStatus::options())
                        ->required(),
                ])
                ->action(function (array $data, Order $record) {
                    $record->status = $data['status'];
                    if ($record->save()) {
                        Notification::make()
                            ->title('Order Status updated Successfully')
                            ->success()
                            ->send();
                    }

                }),
        ];
    }
}
