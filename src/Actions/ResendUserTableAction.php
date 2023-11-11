<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Actions;

use Akira\FilamentUserInvitation\Notifications\UserInvitationNotification;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class ResendUserTableAction
{
    public static function make(): Action
    {
        return Action::make('resendInvite')
            ->label(__('filament-user-invitation::actions.resend_invite'))
            ->icon('heroicon-o-paper-airplane')
            ->action(function ($record) {
                $record->notify(new UserInvitationNotification());
                Notification::make('UserInvitationNotification')
                    ->body(__('User invitation sent successfully.'))
                    ->success()
                    ->send();
            })->requiresConfirmation();

    }
}
