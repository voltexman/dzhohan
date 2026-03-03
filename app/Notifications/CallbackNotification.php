<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class CallbackNotification extends Notification
{
    use Queueable;

    public $phone;

    public $wait;

    public $time_from;

    public $time_to;

    public function __construct($phone, bool $wait, $time_from, $time_to)
    {
        $this->phone = $phone;
        $this->wait = $wait;
        $this->time_from = $time_from;
        $this->time_to = $time_to;
    }

    public function via(): array
    {
        return ['telegram'];
    }

    public function toTelegram(): TelegramMessage
    {
        return TelegramMessage::create()
            ->line('*Прохання передзвонити*')
            ->line("*Телефон:* {$this->phone}")
            ->lineIf(! $this->wait, '*Очікує зараз*')
            ->lineIf($this->wait, "*З:* {$this->time_from}")
            ->lineIf($this->wait, "*До:* {$this->time_to}");
    }
}
