<?php

namespace App\Services\Twitter;

use OauthPhirehose;

class TwitterStream extends OauthPhirehose
{
    /** @var callable */
    public $whenHears;

    /**
     * Enqueue each status
     *
     * @param string $status
     */
    public function enqueueStatus($status)
    {
        ($this->whenHears)($status);
    }

    public function whenHears(string $listenFor, callable $whenHears)
    {
        $this->setTrack([$listenFor]);

        $this->whenHears = $whenHears;

        return $this;
    }

    public function startListening()
    {
        $this->consume();
    }
}