<?php

namespace Tests\Feature\Tiles\BelgianTrains;

use Tests\TestCase;
use Livewire\Livewire;
use Spatie\BelgianTrainsTile\TrainConnectionsStore;
use Spatie\BelgianTrainsTile\BelgianTrainsTileComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BelgianTrainsTileComponentTest extends TestCase
{
    use RefreshDatabase;

    public function testItAliasesTheRotterdamDestinationAndPreservesOtherStationNames(): void
    {
        $trains = collect([
            'Breda (NL) & Rotterdam Centraal (NL)',
            'Breda (NL) & Rotterdam Centraal (NL) ',
            'Noorderkempen',
        ])->map(fn (string $station): array => [
            'station' => $station,
            'time' => (string) now()->timestamp,
            'platform' => '1',
            'canceled' => false,
            'delay' => 0,
        ])->all();

        TrainConnectionsStore::make()->setTrainConnections([
            ['label' => 'Brecht', 'trains' => $trains],
        ]);

        Livewire::test(BelgianTrainsTileComponent::class, ['position' => 'a1:a14'])
            ->assertSee('Brecht')
            ->assertSee('Rotterdam')
            ->assertDontSee('Breda (NL)')
            ->assertDontSee('Rotterdam Centraal (NL)')
            ->assertSee('Noorderkempen');
    }
}
