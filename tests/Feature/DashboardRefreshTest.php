<?php

namespace Tests\Feature;

use App\Models\Deploy;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRefreshTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.access_token', 'test-token');
    }

    public function testDeployStatusRequiresTheDashboardAccessToken(): void
    {
        $this->get('/deploy-status')
            ->assertUnauthorized();
    }

    public function testDeployStatusReturnsTheLatestDeployWithoutCaching(): void
    {
        Deploy::create();
        $latestDeploy = Deploy::create();

        $this->get('/deploy-status?access-token=test-token')
            ->assertOk()
            ->assertHeader('Cache-Control', 'no-store, private')
            ->assertExactJson([
                'deployId' => $latestDeploy->id,
            ]);
    }

    public function testDeployStatusReturnsZeroBeforeTheFirstDeploy(): void
    {
        $this->get('/deploy-status?access-token=test-token')
            ->assertOk()
            ->assertExactJson([
                'deployId' => 0,
            ]);
    }

    public function testDashboardPollsDeployStatusWithoutLivewire(): void
    {
        $deploy = Deploy::create();

        config()->set('dashboard.screens', [
            'external-status' => [
                'url' => 'https://example.com/status',
            ],
        ]);
        config()->set('dashboard.schedule', [
            [
                'screen' => 'external-status',
                'duration_in_seconds' => 60,
            ],
        ]);

        $this->travelTo(CarbonImmutable::parse('2026-04-13 11:59:00', 'Europe/Brussels'));

        $this->get('/?access-token=test-token')
            ->assertOk()
            ->assertViewHas('loadedDeployId', $deploy->id)
            ->assertSee('/deploy-status', false)
            ->assertSee('fetch(deployStatusUrl', false)
            ->assertSee('deployStatusUrl.search = window.location.search', false)
            ->assertSee('window.setTimeout(checkForDeploy, 30_000)', false)
            ->assertDontSee('deploy-checker', false)
            ->assertDontSee('wire:poll.30s', false);
    }

    public function testRefreshCommandCreatesADeploy(): void
    {
        $this->artisan('dashboard:refresh')
            ->expectsOutput('All open dashboards will refresh shortly.')
            ->assertSuccessful();

        $this->assertDatabaseCount('deploys', 1);
    }
}
