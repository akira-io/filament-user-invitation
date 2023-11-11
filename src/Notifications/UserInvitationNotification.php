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
            ->greeting(__('Hello!'))
            ->line(__('You have been invited to join') . config('app.name'))
            ->line(__('To accept this invitation, click the button below'))
            ->action(__('Accept the Invitation'), $this->signedUrl($notifiable))
            ->line(__('If you did not expect to receive an invitation to this team, you may discard this email'))
            ->line('Thank you for using our application!')
            ->salutation(__('Regards'))
            ->subject(__('Invitation to join') . config('app.name'));
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
