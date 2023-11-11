<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Models;

use Illuminate\Foundation\Auth\User as BaseUser;

class User extends BaseUser
{
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
