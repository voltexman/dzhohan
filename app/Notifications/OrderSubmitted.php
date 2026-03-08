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
        $o = $this->order;
        $c = collect($o->custom_options)->collapse(); // Схлопуємо всі вкладені масиви в один

        return TelegramMessage::create()
            ->options(['parse_mode' => 'html'])
            ->line("📦 <b>Замовлення:</b> №{$o->number}")
            ->line('👤 <b>Замовник:</b> '.e("{$o->first_name} {$o->last_name}"))
            ->line("📞 <b>Тел:</b> {$o->phone}")
            ->line('🚚 <b>Адреса:</b> '.e("{$o->city}, {$o->address}"))

            // Список товарів
            ->when($o->products->isNotEmpty(), function ($m) use ($o) {
                $o->products->each(function ($p) use ($m) {
                    $m->line('• '.e($p->product_name)." ({$p->qty}шт.)");
                });
            })

            // Характеристики виготовлення
            ->when(
                $o->type === OrderType::Manufacturing,
                fn ($m) => $m
                    ->line("\n🔪 <b>ВИГОТОВЛЕННЯ:</b>")
                    ->lineIf((bool) $v = $c->get('shape'), "• Форма: {$v}")
                    ->lineIf((bool) $v = $c->get('steel'), "• Сталь: {$v}")
                    ->lineIf((bool) $v = $c->get('grind'), "• Спуски: {$v}")
                    ->lineIf((bool) $v = $c->get('finish'), "• Фініш: {$v}")
                    ->lineIf((bool) $v = $c->get('length'), "• Довжина: {$v} мм")
                    ->lineIf((bool) $v = $c->get('thickness'), "• Товщина: {$v} мм")
                    ->lineIf((bool) $v = $c->get('material'), "• Руків'я: {$v}")
                    ->lineIf((bool) $v = $c->get('color'), "• Колір: {$v}")
                    ->lineIf((bool) $v = $c->get('type'), "• Піхви: {$v}")
                    ->lineIf((bool) $v = $c->get('carry'), "• Носіння: {$v}")
            )

            ->line("\n💰 <b>СУМА:</b> ".number_format($o->total_price, 0).' грн')
            ->lineIf((bool) $o->comment, '💬 '.e($o->comment))
            ->button('В адмінку', route('filament.admin.resources.orders.view', $o));
    }
}
