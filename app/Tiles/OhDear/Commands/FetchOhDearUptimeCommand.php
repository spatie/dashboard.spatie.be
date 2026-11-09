<?php

namespace App\Tiles\OhDear\Commands;

use App\Models\OhDearMessage;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Spatie\Dashboard\Models\Tile;

class FetchOhDearUptimeCommand extends Command
{
    protected $signature = 'dashboard:fetch-oh-dear-uptime';

    protected $description = 'Refresh Oh Dear downtime and clear recovered cron alerts';

    public function handle(): int
    {
        $token = config('services.oh_dear.token');

        if (! is_string($token) || trim($token) === '') {
            $this->error('No Oh Dear API token is configured.');

            return self::FAILURE;
        }

        $downSites = [];
        $recoveredCronChecks = [];
        $page = 1;

        try {
            do {
                $response = Http::acceptJson()
                    ->withToken($token)
                    ->connectTimeout(5)
                    ->timeout(15)
                    ->withoutRedirecting()
                    ->get('https://ohdear.app/api/monitors', ['page' => ['number' => $page]])
                    ->throw();

                $payload = $response->json();

                $data = Validator::make(is_array($payload) && $response->successful() ? $payload : [], [
                    'data' => ['present', 'array', 'list'],
                    'data.*.url' => ['required', 'string'],
                    'data.*.checks' => ['present', 'array', 'list'],
                    'data.*.checks.*.type' => ['required', 'string'],
                    'data.*.checks.*.enabled' => ['required', 'boolean:strict'],
                    'data.*.checks.*.latest_run_ended_at' => ['nullable', 'date'],
                    'data.*.checks.*.latest_run_result' => [
                        'present',
                        'nullable',
                        Rule::in(['succeeded', 'failed', 'warning', 'pending', 'errored-or-timed-out']),
                    ],
                    'meta.current_page' => ['required', 'integer', Rule::in([$page])],
                    'meta.last_page' => ['required', 'integer', "min:{$page}"],
                ])->validate();

                foreach ($data['data'] as $monitor) {
                    foreach ($monitor['checks'] as $check) {
                        if ($check['type'] === 'uptime' && $check['enabled'] && $check['latest_run_result'] === 'failed') {
                            $downSites[] = $monitor['url'];
                        }

                        if ($check['type'] !== 'cron' || ! $check['enabled']) {
                            continue;
                        }

                        if ($check['latest_run_result'] !== 'succeeded' || empty($check['latest_run_ended_at'])) {
                            continue;
                        }

                        $recoveredCronChecks[] = [
                            'site' => $monitor['url'],
                            'recovered_at' => Carbon::parse($check['latest_run_ended_at'], 'UTC')->setTimezone(config('app.timezone')),
                        ];
                    }
                }

                $page++;
            } while ($page <= $data['meta']['last_page']);
        } catch (ConnectionException|RequestException|ValidationException $exception) {
            Log::warning('Oh Dear uptime refresh failed; keeping existing downtime data.', [
                'reason' => $exception::class,
                'status' => $exception instanceof RequestException ? $exception->response->status() : null,
            ]);

            $this->error('Could not refresh Oh Dear uptime; keeping existing downtime data.');

            return self::FAILURE;
        }

        Tile::firstOrCreateForName('ohDearUptime')->putData('downSites', array_values(array_unique($downSites)));

        foreach ($recoveredCronChecks as $check) {
            OhDearMessage::query()
                ->where('site', $check['site'])
                ->where('check_type', 'cron')
                ->where('occurred_at', '<=', $check['recovered_at'])
                ->delete();
        }

        $this->comment('Oh Dear uptime updated and recovered cron alerts cleared.');

        return self::SUCCESS;
    }
}
