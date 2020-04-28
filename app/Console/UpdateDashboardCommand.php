<?php

namespace App\Console;

use Illuminate\Console\Command;
use Spatie\TimeWeatherTile\Commands\FetchOpenWeatherMapDataCommand;
use Spatie\VeloTile\FetchVeloStationsCommand;
use Spatie\CalendarTile\FetchCalendarEventsCommand;
use App\Tiles\TeamMember\Commands\FetchTasksCommand;
use Spatie\BelgianTrainsTile\FetchBelgianTrainsCommand;
use App\Tiles\TeamMember\Commands\FetchSlackStatusCommand;
use App\Tiles\Statistics\Commands\FetchGitHubTotalsCommand;
use App\Tiles\TeamMember\Commands\FetchCurrentTracksCommand;
use App\Tiles\Statistics\Commands\FetchPackagistTotalsCommand;

class UpdateDashboardCommand extends Command
{
    protected $signature = 'dashboard:update';

    protected $description = 'Update all components displayed on the dashboard.';

    public function handle()
    {
        $this->call(FetchBelgianTrainsCommand::class);
        $this->call(FetchCurrentTracksCommand::class);
        $this->call(FetchVeloStationsCommand::class);
        $this->call(FetchTasksCommand::class);
        $this->call(FetchSlackStatusCommand::class);
        $this->call(FetchCalendarEventsCommand::class);
        $this->call(FetchGitHubTotalsCommand::class);
        $this->call(FetchPackagistTotalsCommand::class);
        $this->call(FetchOpenWeatherMapDataCommand::class);
    }
}
