<?php

declare(strict_types=1);

namespace Akira\FilamentUserInvitation\Models;

use Illuminate\Database\Eloquent\Model;

class UserInvitation extends Model
{
    protected $table = 'filament_user_invitation_table';

    protected $fillable = ['email'];
}
