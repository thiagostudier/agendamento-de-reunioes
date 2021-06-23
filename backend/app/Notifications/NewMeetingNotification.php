<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMeetingNotification extends Notification
{
    use Queueable;

    protected $name;
    protected $assunto;
    protected $text;

    public function __construct(string $name, string $assunto, string $text)
    {
        $this->name = $name;
        $this->assunto = $assunto;
        $this->text = $text;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->assunto)
            ->line(nl2br($this->text));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
