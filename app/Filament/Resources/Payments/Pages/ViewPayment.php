<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Filament\Resources\Payments\PaymentResource;
use App\Models\Payment;
use App\Services\PaymentService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPayment extends ViewRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('mark_as_paid')
                ->label(fn (Payment $record) => $record->isPaid() ? "Paid" : "Mark as Paid")
                ->hidden(fn (Payment $record) => !$record->order->isCOD())
                ->disabled(fn (Payment $record) => $record->isPaid())
                ->action(fn (Payment $record) => app(PaymentService::class)->markAsPaid($record))
        ];
    }
}
