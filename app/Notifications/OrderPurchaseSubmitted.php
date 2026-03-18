<?php

namespace App\Notifications;

use App\Enums\CurrencyType;
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

    protected function getFormattedTotals(): array
    {
        return $this->order->products
            ->groupBy('currency')
            ->map(
                fn($group, $currency) =>
                CurrencyType::tryFrom($currency)?->format($group->sum(fn($p) => $p->price * $p->qty))
            )
            ->filter()
            ->toArray();
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject("Нове замовлення №{$this->order->number}")
            ->greeting("📦 Замовлення №{$this->order->number}")
            ->line("**Замовник:** {$this->order->first_name} {$this->order->last_name}")
            ->line("**Телефон:** {$this->order->phone}")
            ->lineIf($this->order->email, "**Email:** {$this->order->email}")
            ->lineIf($this->order->city || $this->order->address, "**Адреса:** {$this->order->city}, {$this->order->address}")
            ->line('');

        $message->line('**🛒 Товари:**');

        foreach ($this->order->products as $product) {
            $formattedSubtotal = $product->currency->format($product->price * $product->qty);

            $message->line("• {$product->name} ({$product->qty} шт.) — **{$formattedSubtotal}**");
        }

        $message->line('');

        $totals = $this->getFormattedTotals();

        if (count($totals) === 1) {
            $message->line("**💰 РАЗОМ ДО СПЛАТИ: " . reset($totals) . "**");
        }

        return $message
            ->lineIf($this->order->comment, "💬 Коментар: {$this->order->comment}")
            ->action('Переглянути в адмінці', route('filament.admin.resources.orders.view', $this->order));
    }

    public function toTelegram(): TelegramMessage
    {
        $message = TelegramMessage::create()
            ->options(['parse_mode' => 'html'])
            ->line("📦 <b>Замовлення:</b> №{$this->order->number}")
            ->line('👤 <b>Замовник:</b> ' . e("{$this->order->first_name} {$this->order->last_name}"))
            ->line("📞 <b>Телефон:</b> {$this->order->phone}")
            ->lineIf($this->order->email, "📞 <b>Email:</b> {$this->order->email}")
            ->lineIf(($this->order->city || $this->order->address), '🚚 <b>Адреса:</b> ' . e("{$this->order?->city}, {$this->order?->address}"))
            ->line("\n<b>🛒 Товари:</b>");

        foreach ($this->order->products as $product) {
            $priceText = $product->currency->format($product->price * $product->qty);

            $message->line('• ' . e($product->name) . " ({$product->qty} шт.) — <b>{$priceText}</b>");
        }

        $message->line("");

        $totals = $this->getFormattedTotals();

        if (count($totals) === 1) {
            $message->line("💰 <b>РАЗОМ ДО СПЛАТИ: " . reset($totals) . "</b>");
        }

        return $message
            ->lineIf($this->order->comment, '💬 ' . e($this->order->comment))
            ->button('В адмінку', route('filament.admin.resources.orders.view', $this->order));
    }
}
