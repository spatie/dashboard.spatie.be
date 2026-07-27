<?php

namespace Tests\Feature\Tiles\Climate;

use Tests\TestCase;
use Livewire\Livewire;
use App\Tiles\Climate\ClimateStore;
use App\Tiles\Climate\ClimateTileComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClimateTileComponentTest extends TestCase
{
    use RefreshDatabase;

    public function testItRendersUnavailableValuesBeforeTheFirstFetch(): void
    {
        Livewire::test(ClimateTileComponent::class, ['position' => 'd1:d6'])
            ->assertSee('🌡️ Climate')
            ->assertSee('Indoor')
            ->assertSee('Outdoor')
            ->assertSee('—')
            ->assertSee('AC —')
            ->assertSee('wire:poll.30s', false);
    }

    public function testItRendersTheTemperaturesAndActiveAcState(): void
    {
        ClimateStore::make()->setClimateData(21.9, 11.3, true);

        Livewire::test(ClimateTileComponent::class, ['position' => 'd1:d6'])
            ->assertSee('21.9°')
            ->assertSee('11.3°')
            ->assertSee('AC on');
    }

    public function testItRendersTheInactiveAcState(): void
    {
        ClimateStore::make()->setClimateData(20.0, 9.0, false);

        Livewire::test(ClimateTileComponent::class, ['position' => 'd1:d6'])
            ->assertSee('20.0°')
            ->assertSee('9.0°')
            ->assertSee('AC off');
    }
}
