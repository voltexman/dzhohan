<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class OrderManufactureSubmitted extends Notification
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

    public function toMail(object $notifiable): MailMessage
    {
        $manufacture = $this->order->manufacture;

        return (new MailMessage)
            ->subject("🛠 Замовлення на виготовлення №{$this->order->number}")
            ->greeting("🛠 Деталі замовлення №{$this->order->number}")
            ->line("**Замовник:** {$this->order->first_name} {$this->order?->last_name}")
            ->line("**Телефон:** {$this->order->phone}")
            ->lineIf($this->order->city || $this->order->address, "**Адреса:** {$this->order?->city}, {$this->order?->address}")
            ->line('')
            ->line('**📐 Параметри виробу:**')

            ->lineIf($manufacture->knife_type, "• **Тип:** {$manufacture->knife_type}")
            ->lineIf($manufacture->blade_steel, "• **Сталь:** {$manufacture->blade_steel}")
            ->lineIf($manufacture->blade_shape, "• **Форма клинка:** {$manufacture->blade_shape}")
            ->lineIf($manufacture->blade_grind, "• **Спуски:** {$manufacture->blade_grind}")
            ->lineIf($manufacture->blade_finish, "• **Фініш:** {$manufacture->blade_finish}")
            ->lineIf($manufacture->handle_material, "• **Руків'я:** {$manufacture->handle_material} ({$manufacture->handle_color})")
            ->lineIf($manufacture->sheath, "• **Піхви:** {$manufacture->sheath}")
            ->lineIf($manufacture->engraving_text, "• **Гравіювання:** {$manufacture->engraving_text}")

            ->line('')
            ->lineIf($this->order->comment, "💬 **Коментар:** {$this->order->comment}")
            ->action('Переглянути в адмінці', route('filament.admin.resources.orders.view', $this->order));
    }

    public function toTelegram($notifiable): TelegramMessage
    {
        $manufacture = $this->order->manufacture;

        return TelegramMessage::create()
            ->options(['parse_mode' => 'html'])
            ->line("🛠 <b>Замовлення на виготовлення №{$this->order->number}</b>")
            ->line('👤 <b>Замовник:</b> '.e("{$this->order->first_name} {$this->order->last_name}"))
            ->line("📞 <b>Телефон:</b> {$this->order->phone}")
            ->lineIf((bool) ($this->order->city || $this->order->address), '🚚 <b>Адреса:</b> '.e("{$this->order?->city}, {$this->order?->address}"))
            ->line("\n<b>📐 Параметри виробу:</b>")

            ->lineIf((bool) $manufacture->knife_type, "• <b>Тип:</b> {$manufacture->knife_type}")
            ->lineIf((bool) $manufacture->blade_shape, "• <b>Форма клинка:</b> {$manufacture->blade_shape}")
            ->lineIf((bool) $manufacture->blade_steel, "• <b>Сталь:</b> {$manufacture->blade_steel}")
            ->lineIf((bool) $manufacture->blade_grind, "• <b>Спуски:</b> {$manufacture->blade_grind}")
            ->lineIf((bool) $manufacture->blade_finish, "• <b>Фініш:</b> {$manufacture->blade_finish}")
            ->lineIf((bool) $manufacture->blade_length, "• <b>Довжина клинка:</b> {$manufacture->blade_length} мм")
            ->lineIf((bool) $manufacture->blade_thickness, "• <b>Товщина обуха:</b> {$manufacture->blade_thickness} мм")
            ->lineIf((bool) $manufacture->handle_material, "• <b>Руків'я:</b> {$manufacture->handle_material} ({$manufacture->handle_color})")
            ->lineIf((bool) $manufacture->engraving_text, "• <b>Гравіювання:</b> {$manufacture->engraving_text}")
            ->lineIf((bool) $manufacture->notes, "• <b>Нотатки:</b> {$manufacture->notes}")

            ->line('')
            ->when($this->order->comment, fn ($message) => $message->line('💬 <b>Коментар:</b> '.e($this->order->comment)))
            ->button('В адмінку', route('filament.admin.resources.orders.view', $this->order));
    }
}