<?php

namespace App\Dashboard\Conditions;

use App\Models\NowPlayingSong;
use App\Dashboard\ScreenCondition;

class NowPlayingScreenCondition implements ScreenCondition
{
    public function shouldDisplay(): bool
    {
        return NowPlayingSong::current() !== null;
    }
}
