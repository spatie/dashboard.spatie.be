<?php

namespace App\Console\Commands;

use App\Models\OhDearMessage;
use Illuminate\Console\Command;

class ClearOhDearMessagesCommand extends Command
{
    protected $signature = 'oh-dear:clear-messages';

    protected $description = 'Clear all Oh Dear messages';

    public function handle(): void
    {
        $count = OhDearMessage::query()->count();

        OhDearMessage::query()->delete();

        $this->comment("Cleared {$count} Oh Dear messages.");
    }
}
