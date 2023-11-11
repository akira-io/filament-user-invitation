<?php

namespace Akira\FilamentUserInvitation;

use Akira\FilamentUserInvitation\Livewire\AcceptInvitation;
use Akira\FilamentUserInvitation\Resources\UserInvitationResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentUserInvitationPlugin implements Plugin
{
    public static function make(): static
    {

        return app(static::class);
    }

    public static function get(): static
    {

        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {

        return 'filament-user-invitation';
    }

    public function register(Panel $panel): void
    {

        $panel
            ->resources([UserInvitationResource::class])
            ->livewireComponents([AcceptInvitation::class])
            ->routes(function ($router) {
                AcceptInvitation::routes($router);
            });
    }

    public function boot(Panel $panel): void
    {
        $this->invitationRoute();
    }

    public function invitationRoute(): ?string
    {
        return filament()->getUrl() . '/invitation/{invitation}/';
        //        return config('filament-user-invitation.accept_invitation_route');
    }
}
