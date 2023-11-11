<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Actions;

use Akira\FilamentUserInvitation\Mail\UserInvitationMail;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;

class ResendUserTableAction
{
    public static function make(): Action
    {
        return Action::make('resendInvite')
            ->label(__('Resend Invite'))
            ->icon('heroicon-o-paper-airplane')
            ->action(function ($record) {

                Mail::to($record->email)->send(new UserInvitationMail($record));
                Notification::make('UserInvitationNotification')
                    ->body(__('User invitation sent successfully.'))
                    ->icon('heroicon-o-done')
                    ->success()
                    ->send();
            })->requiresConfirmation();

    }
}
