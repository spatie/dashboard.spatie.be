<?php

namespace Tests\Feature\Services\Officient;

use App\Services\Officient\Exceptions\RateLimitExceeded;
use App\Services\Officient\Officient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Sleep;
use Tests\TestCase;

class OfficientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Sleep::fake();
    }

    public function testItRetriesAThrottledRequestAfterTheRetryAfterHeader(): void
    {
        $mockHandler = new MockHandler([
            $this->tooManyRequestsResponse(['Retry-After' => '2']),
            $this->personDetailResponse(),
        ]);

        $detail = Officient::create('token', $mockHandler)->getPersonDetail(1);

        $this->assertSame('https://example.com/1.jpg', $detail['avatar']);
        $this->assertSame(0, $mockHandler->count());
        Sleep::assertSequence([
            Sleep::for(2)->seconds(),
            Sleep::for(250)->milliseconds(),
        ]);
    }

    public function testItBacksOffAFewSecondsWhenNoRetryAfterHeaderIsPresent(): void
    {
        $mockHandler = new MockHandler([
            $this->tooManyRequestsResponse(),
            $this->personDetailResponse(),
        ]);

        Officient::create('token', $mockHandler)->getPersonDetail(1);

        Sleep::assertSlept(fn ($duration) => $duration->totalSeconds === 5.0);
    }

    public function testItGivesUpAfterThreeRetries(): void
    {
        $mockHandler = new MockHandler([
            $this->tooManyRequestsResponse(['Retry-After' => '1']),
            $this->tooManyRequestsResponse(['Retry-After' => '1']),
            $this->tooManyRequestsResponse(['Retry-After' => '1']),
            $this->tooManyRequestsResponse(['Retry-After' => '1']),
            $this->personDetailResponse(),
        ]);

        try {
            Officient::create('token', $mockHandler)->getPersonDetail(1);

            $this->fail('Expected a RateLimitExceeded exception to be thrown.');
        } catch (RateLimitExceeded $exception) {
            $this->assertInstanceOf(ClientException::class, $exception->getPrevious());
            $this->assertSame(429, $exception->getPrevious()->getResponse()->getStatusCode());
        }

        $this->assertSame(1, $mockHandler->count());
        Sleep::assertSlept(fn ($duration) => $duration->totalSeconds === 1.0, times: 3);
    }

    public function testItPausesBetweenRequests(): void
    {
        $mockHandler = new MockHandler([
            $this->personDetailResponse(),
            $this->personDetailResponse(),
            $this->personDetailResponse(),
        ]);

        $officient = Officient::create('token', $mockHandler);

        $officient->getPersonDetail(1);
        $officient->getPersonDetail(1);
        $officient->getPersonDetail(1);

        Sleep::assertSequence([
            Sleep::for(250)->milliseconds(),
            Sleep::for(250)->milliseconds(),
        ]);
    }

    private function tooManyRequestsResponse(array $headers = []): Response
    {
        return new Response(429, $headers, json_encode(['message' => 'Too many request. Max 30 per 5 seconds.']));
    }

    private function personDetailResponse(): Response
    {
        return new Response(200, [], json_encode(['data' => ['avatar' => 'https://example.com/1.jpg']]));
    }
}
