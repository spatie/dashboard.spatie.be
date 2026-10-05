<?php

namespace App\Services\Officient;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Sleep;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Officient allows 30 requests per 5 seconds per account token, which is shared
 * with other internal apps. This pauses between requests and retries throttled ones.
 */
class RateLimitMiddleware
{
    protected bool $hasSentRequest = false;

    public function __construct(
        protected int $millisecondsBetweenRequests = 250,
        protected int $maxRetries = 3,
        protected int $defaultRetryAfterSeconds = 5,
        protected int $maxRetryAfterSeconds = 30,
    ) {}

    public function __invoke(callable $handler): callable
    {
        return fn (RequestInterface $request, array $options): PromiseInterface => $this->send($handler, $request, $options);
    }

    /** @param array<string, mixed> $options */
    protected function send(callable $handler, RequestInterface $request, array $options, int $attempt = 0): PromiseInterface
    {
        $this->pauseBetweenRequests();

        return $handler($request, $options)->then(function (ResponseInterface $response) use ($handler, $request, $options, $attempt) {
            if ($response->getStatusCode() !== 429) {
                return $response;
            }

            if ($attempt >= $this->maxRetries) {
                return $response;
            }

            Sleep::for($this->retryAfterSeconds($response))->seconds();

            return $this->send($handler, $request, $options, $attempt + 1);
        });
    }

    protected function pauseBetweenRequests(): void
    {
        if ($this->hasSentRequest) {
            Sleep::for($this->millisecondsBetweenRequests)->milliseconds();
        }

        $this->hasSentRequest = true;
    }

    protected function retryAfterSeconds(ResponseInterface $response): int
    {
        $retryAfter = $response->getHeaderLine('Retry-After');

        if (! is_numeric($retryAfter)) {
            return $this->defaultRetryAfterSeconds;
        }

        return min(max((int) ceil((float) $retryAfter), 1), $this->maxRetryAfterSeconds);
    }
}
