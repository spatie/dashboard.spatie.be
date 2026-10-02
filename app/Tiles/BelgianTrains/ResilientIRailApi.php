<?php

namespace App\Tiles\BelgianTrains;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Spatie\BelgianTrainsTile\IRailApi;
use Spatie\BelgianTrainsTile\TrainConnectionsStore;

/**
 * iRail regularly answers with an error or times out. Instead of reporting every hiccup
 * and blanking the tile, we log a warning and keep showing the last known trains.
 * The next scheduled fetch simply tries again.
 */
class ResilientIRailApi extends IRailApi
{
    protected bool $receivedInvalidResponse = false;

    /** @var array<int, class-string> */
    protected array $expectedRequestExceptions = [
        ConnectionException::class,
        GuzzleException::class,
    ];

    public function getConnections(
        string $departureStationName,
        string $destinationStationName,
        string $locale,
        ?string $label = null,
    ): array {
        $this->receivedInvalidResponse = false;

        $trains = parent::getConnections($departureStationName, $destinationStationName, $locale, $label);

        if (! $this->receivedInvalidResponse) {
            return $trains;
        }

        if ($trains !== []) {
            return $trains;
        }

        return $this->lastKnownTrains($label);
    }

    protected function reportInvalidResponse(string $reason, array $context): void
    {
        $this->receivedInvalidResponse = true;

        if ($this->causedByUnexpectedException($context)) {
            parent::reportInvalidResponse($reason, $context);

            return;
        }

        Log::warning("Received invalid iRail response: {$reason}. Keeping the last known trains.", $context);
    }

    /** @param array<string, mixed> $context */
    protected function causedByUnexpectedException(array $context): bool
    {
        $exceptionClass = $context['exception_class'] ?? null;

        if ($exceptionClass === null) {
            return false;
        }

        return ! collect($this->expectedRequestExceptions)
            ->contains(fn (string $expectedException) => is_a($exceptionClass, $expectedException, true));
    }

    /** @return array<int, array<string, mixed>> */
    protected function lastKnownTrains(?string $label): array
    {
        $lastKnownConnection = collect(TrainConnectionsStore::make()->trainConnections())
            ->firstWhere('label', $label);

        return $lastKnownConnection['trains'] ?? [];
    }
}
