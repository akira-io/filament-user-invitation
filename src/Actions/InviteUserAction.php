<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Actions;

use Akira\FilamentUserInvitation\Mail\UserInvitationMail;
use Akira\FilamentUserInvitation\Models\User;
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

                $user = User::where('email', $data['email'])->first();

                if ($user) {
                    Notification::make('UserInvitationNotification')
                        ->body(__('User already exists.'))
                        ->icon('heroicon-o-exclamation')
                        ->danger()
                        ->send();

                    return;
                }

                $invitation = UserInvitation::create($data);
                Mail::to($invitation->email)->send(new UserInvitationMail($invitation));

                Notification::make('UserInvitationNotification')
                    ->body(__('User invitation sent successfully.'))
                    ->icon('heroicon-o-done')
                    ->success()
                    ->send();
            });

    }
}
