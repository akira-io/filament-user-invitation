<?php

// config for Akira/FilamentUserInvitation
use Filament\Support\Colors\Color;

return [
    'colors' => [
        'primary' => Color::Purple,
    ],

    'accept_invitation_route' => 'invitation/{invitation}/accept',
];
