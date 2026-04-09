<?php

namespace App\Tiles\Officient\Commands;

use App\Services\Officient\Officient;
use App\Tiles\Officient\OfficientStore;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class FetchOfficientCalendarCommand extends Command
{
    protected $signature = 'dashboard:fetch-officient-calendar';

    protected $description = 'Fetch calendar data from Officient to determine who is in the office';

    public function handle(Officient $officient): void
    {
        $this->info('Fetching Officient calendar data...');

        $today = now()->timezone('Europe/Brussels');
        $monday = $today->copy()->startOfWeek();
        $friday = $today->copy()->endOfWeek()->subDays(2);
        $weekDays = CarbonPeriod::create($monday, $friday);

        $people = $this->getPeopleWithAvatars($officient);
        $wfhKeywords = config('dashboard.tiles.officient.wfh_keywords', []);

        $days = [];

        foreach ($weekDays as $day) {
            $inOffice = [];

            foreach ($people as $person) {
                try {
                    $calendar = $officient->getDayCalendar($person['id'], $day);

                    $events = collect($calendar['time_off'] ?? [])
                        ->flatMap(fn (array $dayData) => $dayData['events'] ?? []);

                    $status = $this->determineStatus($events, $wfhKeywords);

                    if ($status === 'office') {
                        $inOffice[] = [
                            'name' => $person['name'],
                            'avatar' => $person['avatar'],
                        ];
                    }
                } catch (Throwable $e) {
                    $this->error("Failed for {$person['name']} on {$day->format('l')}: {$e->getMessage()}");
                }
            }

            $days[] = [
                'date' => $day->format('Y-m-d'),
                'label' => $day->locale('en')->dayName,
                'is_today' => $day->isSameDay($today),
                'in_office' => $inOffice,
            ];

            $this->info("{$day->format('l')}: " . count($inOffice) . ' in office');
        }

        OfficientStore::make()->setWeek($days);

        $this->comment('All done!');
    }

    private function getPeopleWithAvatars(Officient $officient): Collection
    {
        return collect(cache()->remember('officient_people', now()->addDay(), function () use ($officient) {
            return $officient->getPeople()
                ->map(function (array $person) use ($officient) {
                    try {
                        $detail = $officient->getPersonDetail($person['id']);
                        $avatar = $detail['avatar'] ?? null;
                    } catch (Throwable $e) {
                        $avatar = null;
                    }

                    return [
                        'id' => $person['id'],
                        'name' => $person['name'],
                        'avatar' => $avatar ?: gravatar($person['email'] ?? ''),
                    ];
                })
                ->toArray();
        }));
    }

    private function determineStatus(Collection $events, array $wfhKeywords): string
    {
        if ($events->isEmpty()) {
            return 'office';
        }

        foreach ($events as $event) {
            $eventName = Str::lower($event['name'] ?? '');
            $eventType = $event['event_type'] ?? '';

            foreach ($wfhKeywords as $keyword) {
                if (Str::contains($eventName, Str::lower($keyword))) {
                    return 'home';
                }

                if (Str::contains($eventType, Str::lower($keyword))) {
                    return 'home';
                }
            }
        }

        return 'absent';
    }
}
