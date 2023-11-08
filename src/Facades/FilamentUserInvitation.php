<?php

namespace Akira\FilamentUserInvitation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Akira\FilamentUserInvitation\FilamentUserInvitation
 */
class FilamentUserInvitation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Akira\FilamentUserInvitation\FilamentUserInvitation::class;
    }
}
