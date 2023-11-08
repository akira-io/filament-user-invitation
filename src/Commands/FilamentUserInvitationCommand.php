<?php

namespace Akira\FilamentUserInvitation\Commands;

use Illuminate\Console\Command;

class FilamentUserInvitationCommand extends Command
{
    public $signature = 'filament-user-invitation';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
