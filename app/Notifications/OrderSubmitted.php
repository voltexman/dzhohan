<?php

namespace App\Notifications;

use App\Enums\Order\OrderType;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderSubmitted extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    public function toTelegram(): TelegramMessage
    {
        $message = TelegramMessage::create()
            ->options(['parse_mode' => 'html'])
            ->line("📦 <b>Замовлення:</b> №{$this->order->number}")
            ->line("👤 <b>Замовник:</b> " . e("{$this->order->first_name} {$this->order->last_name}"))
            ->line("📞 <b>Телефон:</b> {$this->order->phone}")
            ->line("🚚 <b>Адреса:</b> " . e("{$this->order->city}, {$this->order->address}"))
            ->line("\n<b>🛒 Товари:</b>");

        $this->order->products->each(
            fn($product) =>
            $message->line("• " . e($product->product_name) . " ({$product->qty} шт.)")
        );

        $total = $this->order->products->sum(fn($product) => $product->price * $product->qty);

        return $message
            ->line("\n💰 <b>СУМА:</b> " . number_format($total, 0) . " грн")
            ->lineIf($this->order->comment, "💬 " . e($this->order->comment))
            ->button('В адмінку', route('filament.admin.resources.orders.view', $this->order));
    }
}
