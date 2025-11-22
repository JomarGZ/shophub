<?php

namespace App\Listeners;

use App\Services\StripeWebHookService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(protected StripeWebHookService $stripeWebhookService) {}

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        $type = $event->payload['type'] ?? 'unknown';
        $data = $event->payload['data']['object'] ?? [];
        // Log all events with basic info
        Log::info('Stripe webhook received', [
            'event_id' => $event->payload['id'] ?? null,
            'type' => $type,
            'created' => $event->payload['created'] ?? null,
        ]);

        // Detailed logs for each e-commerce event
        switch ($type) {
            case 'checkout.session.completed':
                $this->stripeWebhookService->handleCheckoutCompleted($data);
                break;

            case 'checkout.session.async_payment_succeeded':
                $this->stripeWebhookService->handleAsyncPaymentSucceeded($data);
                break;

            case 'checkout.session.async_payment_failed':
                $this->stripeWebhookService->handleAsyncPaymentFailed($data);
                break;

            default:
                Log::info('Unhandled Stripe event type', ['type' => $type]);
        }
    }
}
