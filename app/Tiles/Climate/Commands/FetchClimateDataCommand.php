<?php

namespace App\Tiles\Climate\Commands;

use Throwable;
use Illuminate\Console\Command;
use App\Tiles\Climate\ClimateStore;
use Illuminate\Support\Facades\Http;

class FetchClimateDataCommand extends Command
{
    protected $signature = 'dashboard:fetch-climate-data';

    protected $description = 'Fetch climate data';

    public function handle(): int
    {
        $this->info('Fetching climate data...');

        $token = config('services.climate.token');

        if (! is_string($token) || $token === '') {
            $this->error('No climate API token is configured.');

            return self::FAILURE;
        }

        try {
            $response = Http::acceptJson()
                ->withHeaders(['x-api-key' => $token])
                ->get('https://ac.spatie.be/api/status');

            if (! $response->successful()) {
                $this->error('The climate API request failed.');

                return self::FAILURE;
            }

            $payload = $response->json();
        } catch (Throwable) {
            $this->error('The climate API request failed.');

            return self::FAILURE;
        }

        $climateData = is_array($payload) ? $this->aggregateClimateData($payload) : null;

        if ($climateData === null) {
            $this->error('The climate API returned invalid data.');

            return self::FAILURE;
        }

        ClimateStore::make()->setClimateData(
            $climateData['indoorTemperature'],
            $climateData['outdoorTemperature'],
            $climateData['acOn'],
        );

        $this->comment('Climate data updated.');

        return self::SUCCESS;
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array{
     *     indoorTemperature: float,
     *     outdoorTemperature: float,
     *     acOn: bool,
     * }|null
     */
    private function aggregateClimateData(array $payload): ?array
    {
        $units = $payload['units'] ?? null;

        if (! is_array($units) || ! array_is_list($units) || $units === []) {
            return null;
        }

        $indoorTemperatures = [];
        $outdoorTemperatures = [];
        $acStates = [];

        foreach ($units as $unit) {
            if (! $this->isValidUnit($unit)) {
                return null;
            }

            if ($unit['indoorTemperature'] !== null) {
                $indoorTemperatures[] = $unit['indoorTemperature'];
            }

            if ($unit['outdoorTemperature'] !== null) {
                $outdoorTemperatures[] = $unit['outdoorTemperature'];
            }

            $acStates[] = $unit['ac']['on'];
        }

        if ($indoorTemperatures === [] || $outdoorTemperatures === []) {
            return null;
        }

        return [
            'indoorTemperature' => round(array_sum($indoorTemperatures) / count($indoorTemperatures), 1),
            'outdoorTemperature' => round(array_sum($outdoorTemperatures) / count($outdoorTemperatures), 1),
            'acOn' => in_array(true, $acStates, true),
        ];
    }

    private function isValidUnit(mixed $unit): bool
    {
        if (! is_array($unit)) {
            return false;
        }

        if (! array_key_exists('indoorTemperature', $unit)) {
            return false;
        }

        if (! array_key_exists('outdoorTemperature', $unit)) {
            return false;
        }

        if (! is_array($unit['ac'] ?? null)) {
            return false;
        }

        if (! array_key_exists('on', $unit['ac'])) {
            return false;
        }

        if (! $this->isValidTemperature($unit['indoorTemperature'])) {
            return false;
        }

        if (! $this->isValidTemperature($unit['outdoorTemperature'])) {
            return false;
        }

        return is_bool($unit['ac']['on']);
    }

    private function isValidTemperature(mixed $temperature): bool
    {
        return $temperature === null || is_int($temperature) || is_float($temperature);
    }
}
