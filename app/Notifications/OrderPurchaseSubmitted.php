<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderPurchaseSubmitted extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'telegram'];
    }

    public function toMail($notifiable): MailMessage
    {
        $total = $this->order->products->sum(fn ($p) => $p->price * $p->qty);

        $message = (new MailMessage)
            ->subject("Нове замовлення №{$this->order->number}")
            ->greeting("📦 Замовлення №{$this->order->number}")
            ->line("**Замовник:** {$this->order->first_name} {$this->order?->last_name}")
            ->line("**Телефон:** {$this->order->phone}")
            ->lineIf($this->order->email, "**Email:** {$this->order->email}")
            ->lineIf($this->order->city || $this->order->address, "**Адреса:** {$this->order?->city}, {$this->order?->address}")
            ->line('');

        $message->line('**🛒 Товари:**');

        $this->order->products->each(function ($product) use ($message) {
            $message->line("• {$product->name} ({$product->qty} шт.) — ".number_format($product->price * $product->qty, 0).' грн');
        });

        return $message
            ->line('')
            ->line('**💰 СУМА: '.number_format($total, 0).' грн**')
            ->lineIf($this->order->comment, "💬 Коментар: {$this->order->comment}")
            ->action('Переглянути в адмінці', route('filament.admin.resources.orders.view', $this->order));
    }

    public function toTelegram(): TelegramMessage
    {
        $message = TelegramMessage::create()
            ->options(['parse_mode' => 'html'])
            ->line("📦 <b>Замовлення:</b> №{$this->order->number}")
            ->line('👤 <b>Замовник:</b> '.e("{$this->order->first_name} {$this->order->last_name}"))
            ->line("📞 <b>Телефон:</b> {$this->order->phone}")
            ->lineIf($this->order->email, "📞 <b>Email:</b> {$this->order->email}")
            ->lineIf(($this->order->city || $this->order->address), '🚚 <b>Адреса:</b> '.e("{$this->order?->city}, {$this->order?->address}"))
            ->line("\n<b>🛒 Товари:</b>");

        $this->order->products->each(
            fn ($product) => $message->line('• '.e($product->name)." ({$product->qty} шт.)")
        );

        $total = $this->order->products->sum(fn ($product) => $product->price * $product->qty);

        return $message
            ->line("\n💰 <b>СУМА:</b> ".number_format($total, 0).' грн')
            ->lineIf($this->order->comment, '💬 '.e($this->order->comment))
            ->button('В адмінку', route('filament.admin.resources.orders.view', $this->order));
    }
}
