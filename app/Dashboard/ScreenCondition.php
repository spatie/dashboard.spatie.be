<?php

namespace App\Dashboard;

interface ScreenCondition
{
    public function shouldDisplay(): bool;
}
