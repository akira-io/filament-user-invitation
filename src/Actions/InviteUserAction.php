<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Actions;

use Akira\FilamentUserInvitation\Models\User;
use Akira\FilamentUserInvitation\Models\UserInvitation;
use Akira\FilamentUserInvitation\Notifications\UserInvitationNotification;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class InviteUserAction
{
    public static function make(): Action
    {

        return Action::make('inviteUser')
            ->label(__('filament-user-invitation::actions.invite_user'))
            ->form([
                TextInput::make('email')
                    ->email()
                    ->required(),
            ])->action(function ($data) {

                // @phpstan-ignore-next-line
                $user = User::query()->where('email', $data['email'])->first();
                $invitation = UserInvitation::query()->where('email', $data['email'])->first();

                if ($user || $invitation) {
                    Notification::make('UserInvitationNotification')
                        ->body(__('User or invitation already exists.'))
                        ->danger()
                        ->send();

                    return;
                }

                $invitation = UserInvitation::create([
                    'email' => $data['email'],
                    'token' => bin2hex(random_bytes(32)),
                    'expires_at' => now()->addDay(),
                ]);

                $invitation->notify(new UserInvitationNotification());

                Notification::make('UserInvitationNotification')
                    ->body(__('User invitation sent successfully.'))
                    ->success()
                    ->send();
            });

    }
}
