<?php

namespace App\Services\Officient;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class Officient
{
    public function __construct(protected Client $client)
    {
    }

    public function getPeople(): Collection
    {
        $people = collect();
        $page = 0;

        do {
            $response = $this->client->get('/1.0/people/list', [
                'query' => ['page' => $page],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            $people = $people->concat($data['data'] ?? []);
            $totalCount = $data['total_record_count'] ?? 0;
            $page++;
        } while ($people->count() < $totalCount);

        return $people->filter(fn (array $person) => $person['archived'] === 0);
    }

    public function getPersonDetail(int $personId): array
    {
        $response = $this->client->get("/1.0/people/{$personId}/detail");

        $data = json_decode((string) $response->getBody(), true);

        return $data['data'] ?? [];
    }

    public function getDayCalendar(int $personId, Carbon $date): array
    {
        $response = $this->client->get("/1.0/calendar/{$personId}/{$date->year}/{$date->month}/{$date->day}");

        $data = json_decode((string) $response->getBody(), true);

        return $data['data'] ?? [];
    }
}
