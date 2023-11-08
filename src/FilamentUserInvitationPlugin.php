<?php

namespace Akira\FilamentUserInvitation;

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

        $panel->resources([UserInvitationResource::class]);
    }

    public function boot(Panel $panel): void
    {
    }
}
