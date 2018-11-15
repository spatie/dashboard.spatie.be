<?php

namespace App\Console\Components\Dashboard;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Events\Dashboard\UpdateAppearance;

class DetermineAppearanceCommand extends Command
{
    protected $signature = 'dashboard:determine-appearance';

    protected $description = 'Determine the looks of the dashboard';

    /** @var float */
    protected $antwerpLat = 51.260197;

    /** @var float */
    protected $antwerpLng = 4.402771;

    public function handle()
    {
        $this->info('Determining dashboard appearance...');

        $appearance = $this->sunIsUp()
            ? 'light-mode'
            : 'dark-mode';

        event(new UpdateAppearance($appearance));

        $this->info('All done!');
    }

    public function sunIsUp(): bool
    {
        $sunriseTimestamp = date_sunrise(
            now()->timestamp,
            SUNFUNCS_RET_TIMESTAMP,
            $this->antwerpLat,
            $this->antwerpLng
        );
        $sunrise = Carbon::createFromTimestamp($sunriseTimestamp);

        $sunsetTimestamp = date_sunset(
            now()->timestamp,
            SUNFUNCS_RET_TIMESTAMP,
            $this->antwerpLat,
            $this->antwerpLng
        );
        $sunset = Carbon::createFromTimestamp($sunsetTimestamp);

        return now()->between($sunrise, $sunset);
    }
}
