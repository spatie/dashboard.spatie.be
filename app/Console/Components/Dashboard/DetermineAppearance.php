<?php

namespace App\Console\Components\Dashboard;

use App\Events\Dashboard\UpdateAppearance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DetermineAppearance extends Command
{
    protected $signature = 'dashboard:determine-appearance';

    protected $description = 'Determine the looks of the dashboard';

    /** @var float */
    protected $antwerpLat = 51.260197;

    /** @var float */
    protected $antwerpLng = 4.402771;

    public function handle()
    {
        $appearance = $this->sunIsUp()
            ? 'light'
            : 'dark';
        dd($appearance);
        event(new UpdateAppearance($appearance));
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