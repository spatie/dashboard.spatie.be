<?php

namespace App\Console\Components\TeamMember;

use App\Support\TeamMemberStore;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use App\Support\Forecast\ForecastApi;

class FetchTasksCommand extends Command
{
    protected $signature = 'dashboard:fetch-tasks';

    protected $description = 'Fetch team members tasks from Forecast';

    public function handle(ForecastApi $forecast)
    {
        $this->info('Fetching tasks from Forecast...');

        $forecast
            ->getThisWeeksTasksFor(
                explode(',', config('services.forecast.people'))
            )
            ->each(function (Collection $tasks, string $personName) {
                TeamMemberStore::find($personName)->setTasks($tasks->toArray());
            });

        $this->info('All done!');
    }
}
