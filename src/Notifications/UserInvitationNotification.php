<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitationNotification extends Notification
{
    public function __construct()
    {
    }

    public function via($notifiable): array
    {

        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {

        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', $this->signedUrl($notifiable))
            ->line('Thank you for using our application!');
    }

    private function signedUrl($notifiable): string
    {
        return filament()->getUrl() . '/invitation/' . $notifiable->token . '/';
    }

    public function toArray($notifiable): array
    {

        return [];
    }
}
