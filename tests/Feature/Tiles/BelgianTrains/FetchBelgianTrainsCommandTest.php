<?php

namespace Tests\Feature\Tiles\BelgianTrains;

use Tests\TestCase;
use RuntimeException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Exceptions;
use Spatie\BelgianTrainsTile\TrainConnectionsStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\BelgianTrainsTile\Exceptions\InvalidIRailResponse;

class FetchBelgianTrainsCommandTest extends TestCase
{
    use RefreshDatabase;

    private array $lastKnownTrainConnections = [
        [
            'label' => 'Gent',
            'trains' => [[
                'station' => 'Gent-Dampoort',
                'time' => '1791100000',
                'platform' => '3',
                'canceled' => false,
                'delay' => 0,
            ]],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('dashboard.tiles.belgian_trains.connections', [[
            'departure' => 'Antwerpen-Centraal',
            'destination' => 'Gent-Dampoort',
            'label' => 'Gent',
        ]]);

        TrainConnectionsStore::make()->setTrainConnections($this->lastKnownTrainConnections);

        Exceptions::fake();
    }

    public function testItStoresTheFetchedTrains(): void
    {
        Http::fake(['api.irail.be/*' => Http::response([
            'connection' => [[
                'departure' => [
                    'direction' => ['name' => 'Gent-Sint-Pieters'],
                    'time' => '1791103600',
                    'platform' => '5',
                    'canceled' => '0',
                    'delay' => '120',
                ],
            ]],
        ])]);

        $this->artisan('dashboard:fetch-belgian-trains')->assertSuccessful();

        $this->assertSame([[
            'label' => 'Gent',
            'trains' => [[
                'station' => 'Gent-Sint-Pieters',
                'time' => '1791103600',
                'platform' => '5',
                'canceled' => false,
                'delay' => 2,
            ]],
        ]], TrainConnectionsStore::make()->trainConnections());

        Exceptions::assertNothingReported();
    }

    public function testItKeepsTheLastKnownTrainsWhenIRailRespondsWithAnError(): void
    {
        Log::spy();

        Http::fake(['api.irail.be/*' => Http::response('Service Unavailable', 503)]);

        $this->artisan('dashboard:fetch-belgian-trains')->assertSuccessful();

        $this->assertSame($this->lastKnownTrainConnections, TrainConnectionsStore::make()->trainConnections());

        Exceptions::assertNothingReported();

        Log::shouldHaveReceived('warning')
            ->withArgs(fn (string $message, array $context) => str_contains($message, 'unexpected status code') && $context['status_code'] === 503)
            ->once();
    }

    public function testItKeepsTheLastKnownTrainsWhenIRailCannotBeReached(): void
    {
        Log::spy();

        Http::fake(['api.irail.be/*' => fn () => throw new ConnectionException('Connection timed out')]);

        $this->artisan('dashboard:fetch-belgian-trains')->assertSuccessful();

        $this->assertSame($this->lastKnownTrainConnections, TrainConnectionsStore::make()->trainConnections());

        Exceptions::assertNothingReported();

        Log::shouldHaveReceived('warning')
            ->withArgs(fn (string $message) => str_contains($message, 'request failed'))
            ->once();
    }

    public function testItKeepsTheLastKnownTrainsWhenIRailReturnsAnUnexpectedPayload(): void
    {
        Http::fake(['api.irail.be/*' => Http::response(['error' => 'No connections found'])]);

        $this->artisan('dashboard:fetch-belgian-trains')->assertSuccessful();

        $this->assertSame($this->lastKnownTrainConnections, TrainConnectionsStore::make()->trainConnections());

        Exceptions::assertNothingReported();
    }

    public function testItStillReportsUnexpectedExceptions(): void
    {
        Http::fake(['api.irail.be/*' => fn () => throw new RuntimeException('Something is broken')]);

        $this->artisan('dashboard:fetch-belgian-trains')->assertSuccessful();

        Exceptions::assertReported(fn (InvalidIRailResponse $exception) => $exception->context()['exception_class'] === RuntimeException::class);
    }
}
