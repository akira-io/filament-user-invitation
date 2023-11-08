<?php

use Akira\FilamentUserInvitation\Livewire\AcceptInvitation;

Route::middleware(['signed', 'web'])
    ->get('/invitation/{invitation}/accept', AcceptInvitation::class)
    ->name('filament-user-invitation.accept-invitation');
