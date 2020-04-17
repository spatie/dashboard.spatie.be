<?php

namespace App\Services\Slack;

class Member
{
    /** @var string */
    public $name;

    /** @var string */
    public $statusEmoji;

    public function __construct(array $memberProperties)
    {
        $this->name = $memberProperties['name'];

        $this->statusEmoji = $this->convertToRealEmoji($memberProperties['profile']['status_emoji'] ?? '');
    }

    protected function convertToRealEmoji(string $statusEmoji): string
    {
        if ($statusEmoji === ':house_with_garden:') {
            return '🏠';
        }

        if ($statusEmoji === ':knife_fork_plate:') {
            return '🍝️';
        }

        if ($statusEmoji === ':palm_tree') {
            return '🌴';
        }

        if ($statusEmoji === ':spiral_calendar_pad:') {
            return '🗓';
        }

        return '';
    }
}
