<?php

namespace App\Console;

use App\Console\DetermineAppearanceCommand;
use App\Console\SendHeartbeatCommand;
use App\Tiles\Statistics\Commands\FetchGitHubTotalsCommand;
use App\Tiles\Statistics\Commands\FetchPackagistTotalsCommand;
use App\Tiles\TeamMember\Commands\FetchCurrentTracksCommand;
use App\Tiles\TeamMember\Commands\FetchSlackStatusCommand;
use App\Tiles\TeamMember\Commands\FetchTasksCommand;
use App\Tiles\Weather\Commands\FetchOpenWeatherDataCommand;
use Illuminate\Console\Command;
use Spatie\BelgianTrainsTile\FetchBelgianTrainsCommand;
use Spatie\CalendarTile\FetchCalendarEventsCommand;
use Spatie\VeloTile\FetchVeloStationsCommand;

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
        $this->call(FetchOpenWeatherDataCommand::class);
    }
}
