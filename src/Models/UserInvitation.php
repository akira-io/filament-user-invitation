<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserInvitation extends Model
{
    use Notifiable;

    protected $table = 'filament_user_invitation_table';

    protected $fillable = ['email', 'token', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
