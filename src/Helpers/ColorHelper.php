<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Helpers;

class ColorHelper
{
    public static function primary(): array
    {
        return config('filament-user-invitation.colors.primary');
    }
}
