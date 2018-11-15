<?php

namespace App\Services\Slack;

class Member
{
    /** @var string */
    public $name;

    /** @var bool */
    public $workingFromHome;

    public function __construct(array $memberProperties)
    {
        $this->name = $memberProperties['name'];

        $this->workingFromHome = $memberProperties['profile']['status_emoji'] === ':house_with_garden:';
    }
}