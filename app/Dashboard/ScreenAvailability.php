<?php

namespace App\Dashboard;

class ScreenAvailability
{
    /**
     * @param  array<string, array<string, mixed>>  $screens
     * @return array<int, string>
     */
    public function availableScreenNames(array $screens): array
    {
        return collect($screens)
            ->filter(fn (array $screen): bool => $this->screenIsAvailable($screen))
            ->keys()
            ->values()
            ->all();
    }

    /** @param array<string, mixed> $screen */
    private function screenIsAvailable(array $screen): bool
    {
        $conditionClass = $screen['condition'] ?? null;

        if ($conditionClass === null) {
            return true;
        }

        if (! is_string($conditionClass)) {
            return false;
        }

        if (! is_a($conditionClass, ScreenCondition::class, true)) {
            return false;
        }

        return app($conditionClass)->shouldDisplay();
    }
}
