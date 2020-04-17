<?php

namespace App\Console;

use App\Console\Components\Dashboard\DetermineAppearanceCommand;
use App\Console\Components\Dashboard\SendHeartbeatCommand;
use App\Console\Components\Trains\FetchTrainsCommand;
use App\Tiles\Calendar\FetchCalendarEventsCommand;
use App\Tiles\Statistics\Commands\FetchGitHubTotalsCommand;
use App\Tiles\Statistics\Commands\FetchPackagistTotalsCommand;
use App\Tiles\TeamMember\Commands\FetchCurrentTracksCommand;
use App\Tiles\TeamMember\Commands\FetchSlackStatusCommand;
use App\Tiles\TeamMember\Commands\FetchTasksCommand;
use App\Tiles\Velo\FetchVeloStationsCommand;
use App\Tiles\Weather\Commands\FetchOpenWeatherDataCommand;
use Illuminate\Console\Command;

class UpdateDashboardCommand extends Command
{
    protected $signature = 'dashboard:update';

    protected $description = 'Update all components displayed on the dashboard.';

    public function handle()
    {
        $this->call(DetermineAppearanceCommand::class);
        $this->call(FetchTrainsCommand::class);
        $this->call(SendHeartbeatCommand::class);
        $this->call(FetchCurrentTracksCommand::class);
        $this->call(FetchVeloStationsCommand::class);
        $this->call(FetchTasksCommand::class);
        $this->call(FetchSlackStatusCommand::class);
        $this->call(FetchCalendarEventsCommand::class);
        $this->call(FetchGitHubTotalsCommand::class);
        $this->call(FetchPackagistTotalsCommand::class);
        $this->class(FetchOpenWeatherDataCommand::class);
    }
}
