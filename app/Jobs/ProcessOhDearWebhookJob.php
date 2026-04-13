<?php

namespace App\Jobs;

use App\Models\OhDearMessage;
use Illuminate\Support\Arr;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessOhDearWebhookJob extends ProcessWebhookJob
{
    private const SEVERITY_MAP = [
        'uptimeCheckFailed' => 'error',
        'certificateExpiresSoon' => 'error',
        'certificateExpired' => 'error',
        'certificateCheckFailed' => 'error',
        'applicationHealthCheckFailed' => 'error',
        'cronCheckFailed' => 'error',
        'mixedContentFound' => 'error',
        'dnsHistoryChanged' => 'error',
        'performanceTargetsNotMet' => 'warning',
        'brokenLinksFound' => 'warning',
        'lighthouseScoreLow' => 'warning',
        'uptimeCheckRecovered' => 'warning',
    ];

    public function handle(): void
    {
        $payload = $this->webhookCall->payload;

        $type = Arr::get($payload, 'type');

        if (! $type || ! isset(self::SEVERITY_MAP[$type])) {
            return;
        }

        $site = Arr::get($payload, 'data.site.url') ?? Arr::get($payload, 'data.site.sort_url');
        $checkType = Arr::get($payload, 'data.check.type', '');

        OhDearMessage::create([
            'event_type' => $type,
            'severity' => self::SEVERITY_MAP[$type],
            'group_key' => sha1("{$type}|{$site}|{$checkType}"),
            'title' => $this->buildTitle($type, $payload),
            'site' => $site,
            'payload' => $payload,
            'occurred_at' => now(),
        ]);
    }

    private function buildTitle(string $type, array $payload): string
    {
        $label = str(preg_replace('/(?<!^)[A-Z]/', ' $0', $type))->lower()->ucfirst()->toString();

        $reason = Arr::get($payload, 'data.reason');

        return $reason ? "{$label}: {$reason}" : $label;
    }
}
