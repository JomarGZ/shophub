<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedCOD extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $orderUrl = '#';

        return (new MailMessage)
            ->subject('New COD Order Placed: #'.$this->order->id)
            ->greeting('Hello Admin,')
            ->line('A new Cash on Delivery (COD) order has been placed by '.$this->order->shipping_full_name.'.')
            ->line('Order ID: '.$this->order->id)
            ->line('Order Total: $'.number_format($this->order->total, 2))
            ->action('View Order Details', $orderUrl)
            ->line('Please process this order promptly.')
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function viaQueues()
    {
        return [
            'mail' => 'mail-queue',
        ];
    }
}
