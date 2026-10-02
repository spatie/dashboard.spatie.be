<?php

namespace App\Services\Officient\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class RateLimitExceeded extends Exception
{
    public static function forRequest(ClientException $exception): self
    {
        return new self(
            "Officient kept rate limiting `{$exception->getRequest()->getUri()}` after retrying.",
            previous: $exception,
        );
    }
}
