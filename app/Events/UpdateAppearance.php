<?php

namespace App\Events;

class UpdateAppearance
{
    /** @var string */
    public $mode;

    public function __construct(string $mode)
    {
        $this->mode = $mode;
    }
}
