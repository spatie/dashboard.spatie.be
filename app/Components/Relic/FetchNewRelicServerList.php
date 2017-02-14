<?php

namespace App\Components\Relic;

use App\Events\Relic\ServerListFetched;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use MongoDB\Driver\Server;


class FetchNewRelicServerList extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:newrelic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the server list from New Relic.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();

        $response = $client->get('https://api.newrelic.com/v2/servers.json', [
            'headers' => [
                'X-Api-Key' => env("NEW_RELIC_API_KEY"),
                'Content-Type' => 'application/json'
            ]
        ])->getBody();

        // only keep servers with summary and remap it
        $serverList = collect(json_decode($response)->servers)
            ->reject(function($server){
                return !isset($server->summary);
            })
            ->map(function($server){
                return [
                    'name' => $server->name,
                    'health' => $server->health_status,
                    'cpu' => $server->summary->cpu,
                    'memory' => $server->summary->memory,
                    'disk_io' => $server->summary->disk_io,
                    'fullest_disk' => $server->summary->fullest_disk
                ];
            })
            ->toArray();

        event(new ServerListFetched($serverList));
    }
}