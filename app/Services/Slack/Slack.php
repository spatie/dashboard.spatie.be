<?php

namespace App\Services\Slack;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;

class Slack
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getMembers(array $memberNames): Collection
    {
        $response = $this->client->get('/api/users.list');

        $response = json_decode((string)$response->getBody(), true);

        return collect($response['members'])
            ->filter(function (array $member) use ($memberNames) {
                return in_array($member['name'], $memberNames);
            })
            ->map(function (array $memberProperties) {
                return new Member($memberProperties);
            });
    }
}
