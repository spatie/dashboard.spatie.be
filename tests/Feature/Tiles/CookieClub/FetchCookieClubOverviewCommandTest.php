<?php

namespace Tests\Feature\Tiles\CookieClub;

use Tests\TestCase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use App\Tiles\CookieClub\CookieClubStore;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FetchCookieClubOverviewCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testItFetchesAndStoresTheCookieClubOverview(): void
    {
        Http::fake([
            'https://cookie-club.spatie.be/api/public/overview' => Http::response($this->validPayload()),
        ]);

        $this->artisan('dashboard:fetch-cookie-club-overview')
            ->expectsOutput('Cookie Club overview updated.')
            ->assertSuccessful();

        Http::assertSent(function (Request $request): bool {
            return $request->method() === 'GET'
                && $request->url() === 'https://cookie-club.spatie.be/api/public/overview'
                && $request->hasHeader('Accept', 'application/json');
        });

        $store = CookieClubStore::make();

        $this->assertEquals([
            'name' => 'Chocolate chip',
            'imageUrl' => 'https://cookie-club.spatie.be/images/chocolate-chip.webp',
            'submittedBy' => 'Alex',
            'score' => 4.25,
            'ratingCount' => 12,
        ], $store->cookieOfTheWeek());

        $this->assertEquals([
            [
                'rank' => 1,
                'name' => 'Speculoos',
                'submittedBy' => 'Dries',
                'score' => 4.75,
                'ratingCount' => 10,
            ],
        ], $store->leaderboard());
    }

    public function testItRetainsStoredDataWhenTheRequestFails(): void
    {
        $this->storeExistingOverview();

        Http::fake([
            'https://cookie-club.spatie.be/api/public/overview' => Http::response([], 500),
        ]);

        $this->artisan('dashboard:fetch-cookie-club-overview')->assertFailed();

        $this->assertSame('Existing cookie', CookieClubStore::make()->cookieOfTheWeek()['name']);
    }

    public function testItRetainsStoredDataWhenTheResponseIsInvalid(): void
    {
        $this->storeExistingOverview();

        $payload = $this->validPayload();
        $payload['leaderboard'][0]['score'] = 'excellent';

        Http::fake([
            'https://cookie-club.spatie.be/api/public/overview' => Http::response($payload),
        ]);

        $this->artisan('dashboard:fetch-cookie-club-overview')->assertFailed();

        $this->assertSame('Existing cookie', CookieClubStore::make()->cookieOfTheWeek()['name']);
    }

    private function storeExistingOverview(): void
    {
        CookieClubStore::make()->setOverview([
            'name' => 'Existing cookie',
            'imageUrl' => 'https://cookie-club.spatie.be/images/existing.webp',
            'submittedBy' => 'Tim',
            'score' => 4.0,
            'ratingCount' => 8,
        ], []);
    }

    private function validPayload(): array
    {
        return [
            'cookie_of_the_week' => [
                'name' => 'Chocolate chip',
                'session_date' => '2026-08-03',
                'image_url' => 'https://cookie-club.spatie.be/images/chocolate-chip.webp',
                'submitted_by' => 'Alex',
                'score' => 4.25,
                'rating_count' => 12,
            ],
            'leaderboard' => [
                [
                    'rank' => 1,
                    'name' => 'Speculoos',
                    'session_date' => '2026-06-29',
                    'image_url' => 'https://cookie-club.spatie.be/images/speculoos.webp',
                    'submitted_by' => 'Dries',
                    'score' => 4.75,
                    'rating_count' => 10,
                ],
            ],
        ];
    }
}
