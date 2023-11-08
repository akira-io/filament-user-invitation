<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Actions;

use Akira\FilamentUserInvitation\Mail\UserInvitationMail;
use Akira\FilamentUserInvitation\Models\UserInvitation;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class InviteUserAction
{
    public static function make(): Action
    {
        return Action::make('inviteUser')
            ->label(__('Invite User'))
            ->form([
                TextInput::make('email')
                    ->email()
                    ->required(),
            ])->action(function ($data) {

                $user = UserInvitation::create($data);
                Mail::to($user->email)->send(new UserInvitationMail($user));

                Notification::make('UserInvitationNotification')
                    ->body(__('User invitation sent successfully.'))
                    ->icon('heroicon-o-done')
                    ->success()
                    ->send();
            });

    }
}
