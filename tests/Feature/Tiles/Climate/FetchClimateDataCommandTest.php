<?php

namespace Tests\Feature\Tiles\Climate;

use Tests\TestCase;
use App\Tiles\Climate\ClimateStore;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchClimateDataCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.climate.token', 'test-api-token');
    }

    public function testItFetchesAndStoresAggregateClimateData(): void
    {
        Http::fake([
            'https://ac.spatie.be/api/status' => Http::response([
                'units' => [
                    [
                        'indoorTemperature' => 21.2,
                        'outdoorTemperature' => 10.1,
                        'ac' => [
                            'on' => false,
                            'targetTemperature' => 20,
                        ],
                    ],
                    [
                        'indoorTemperature' => 22.5,
                        'outdoorTemperature' => 12.4,
                        'ac' => [
                            'on' => true,
                            'targetTemperature' => 21,
                        ],
                    ],
                    [
                        'indoorTemperature' => null,
                        'outdoorTemperature' => null,
                        'ac' => [
                            'on' => false,
                            'targetTemperature' => null,
                        ],
                    ],
                ],
            ]),
        ]);

        $this->artisan('dashboard:fetch-climate-data')
            ->expectsOutput('Climate data updated.')
            ->assertSuccessful();

        Http::assertSent(function (Request $request): bool {
            return $request->method() === 'GET'
                && $request->url() === 'https://ac.spatie.be/api/status'
                && $request->hasHeader('Accept', 'application/json')
                && $request->hasHeader('x-api-key', 'test-api-token');
        });

        $climateStore = ClimateStore::make();

        $this->assertSame(21.9, $climateStore->indoorTemperature());
        $this->assertSame(11.3, $climateStore->outdoorTemperature());
        $this->assertTrue($climateStore->acOn());
    }

    #[DataProvider('invalidResponseProvider')]
    public function testItRetainsStoredDataWhenAResponseIsInvalid(mixed $body, int $status): void
    {
        ClimateStore::make()->setClimateData(19.8, 8.4, false);

        Http::fake([
            'https://ac.spatie.be/api/status' => Http::response($body, $status),
        ]);

        $this->artisan('dashboard:fetch-climate-data')->assertFailed();

        $climateStore = ClimateStore::make();

        $this->assertSame(19.8, $climateStore->indoorTemperature());
        $this->assertSame(8.4, $climateStore->outdoorTemperature());
        $this->assertFalse($climateStore->acOn());
    }

    public static function invalidResponseProvider(): array
    {
        return [
            'failed request' => [
                ['units' => []],
                500,
            ],
            'empty units' => [
                ['units' => []],
                200,
            ],
            'units object' => [
                [
                    'units' => [
                        'office' => [
                            'indoorTemperature' => 21,
                            'outdoorTemperature' => 12,
                            'ac' => ['on' => false],
                        ],
                    ],
                ],
                200,
            ],
            'malformed unit' => [
                [
                    'units' => [
                        [
                            'indoorTemperature' => 'warm',
                            'outdoorTemperature' => 12,
                            'ac' => ['on' => false],
                        ],
                    ],
                ],
                200,
            ],
            'invalid json' => [
                'not json',
                200,
            ],
        ];
    }
}
