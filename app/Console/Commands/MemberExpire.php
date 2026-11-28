<?php

namespace App\Console\Commands;

use App\Services\MembershipStatusService;
use Illuminate\Console\Command;

class MemberExpire extends Command
{
    protected $signature = 'member:expire';

    protected $description = 'Ubah status member yang masa berlakunya sudah lewat menjadi inactive';

    public function handle(MembershipStatusService $memberships): int
    {
        $count = $memberships->expireOverdue();
        $this->info("{$count} member diubah menjadi inactive.");

        return self::SUCCESS;
    }
}
