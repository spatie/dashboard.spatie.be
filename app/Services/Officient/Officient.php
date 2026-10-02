<?php

namespace App\Services\Officient;

use App\Services\Officient\Exceptions\RateLimitExceeded;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Collection;

class Officient
{
    public function __construct(protected Client $client)
    {
    }

    public static function create(string $token, ?callable $handler = null): self
    {
        $handlerStack = HandlerStack::create($handler);

        $handlerStack->push(new RateLimitMiddleware, 'rate_limit');

        $client = new Client([
            'base_uri' => 'https://api.officient.io',
            'handler' => $handlerStack,
            'headers' => [
                'Authorization' => "Bearer {$token}",
            ],
        ]);

        return new self($client);
    }

    public function getPeople(): Collection
    {
        $people = collect();
        $page = 0;

        do {
            $data = $this->get('/1.0/people/list', ['page' => $page]);

            $people = $people->concat($data['data'] ?? []);
            $totalCount = $data['total_record_count'] ?? 0;
            $page++;
        } while ($people->count() < $totalCount);

        return $people->filter(fn (array $person) => $person['archived'] === 0);
    }

    public function getPersonDetail(int $personId): array
    {
        $data = $this->get("/1.0/people/{$personId}/detail");

        return $data['data'] ?? [];
    }

    public function getDayCalendar(int $personId, Carbon $date): array
    {
        $data = $this->get("/1.0/calendar/{$personId}/{$date->year}/{$date->month}/{$date->day}");

        return $data['data'] ?? [];
    }

    /**
     * @param array<string, mixed> $query
     *
     * @return array<string, mixed>
     */
    protected function get(string $uri, array $query = []): array
    {
        try {
            $response = $this->client->get($uri, ['query' => $query]);
        } catch (ClientException $exception) {
            if ($exception->getResponse()->getStatusCode() === 429) {
                throw RateLimitExceeded::forRequest($exception);
            }

            throw $exception;
        }

        return json_decode((string) $response->getBody(), true) ?? [];
    }
}
