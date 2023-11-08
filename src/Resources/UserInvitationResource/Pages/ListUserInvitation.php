<?php

namespace Akira\FilamentUserInvitation\Resources\UserInvitationResource\Pages;

use Akira\FilamentUserInvitation\Actions\InviteUserAction;
use Akira\FilamentUserInvitation\Resources\UserInvitationResource;
use Filament\Resources\Pages\ListRecords;

class ListUserInvitation extends ListRecords
{
    protected static string $resource = UserInvitationResource::class;

    protected function getHeaderActions(): array
    {

        return [
            InviteUserAction::make(),
        ];
    }
}
