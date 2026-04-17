<?php

namespace App\Jobs;

use App\Models\OhDearMessage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessOhDearWebhookJob extends ProcessWebhookJob
{
    public function handle(): void
    {
        $payload = $this->webhookCall->payload;

        $type = Arr::get($payload, 'type');

        if (! $type) {
            return;
        }

        $site = Arr::get($payload, 'site.url') ?? Arr::get($payload, 'site.sort_url');
        $checkType = Arr::get($payload, 'run.check.type', '');

        if ($this->isRecoveryEvent($type)) {
            OhDearMessage::query()
                ->where('site', $site)
                ->where('check_type', $checkType)
                ->where('severity', 'error')
                ->delete();

            return;
        }

        OhDearMessage::create([
            'event_type' => $type,
            'severity' => $this->resolveSeverity($type),
            'group_key' => sha1("{$type}|{$site}|{$checkType}"),
            'check_type' => $checkType,
            'title' => $this->buildTitle($type, $payload),
            'site' => $site,
            'payload' => $payload,
            'occurred_at' => now(),
        ]);
    }

    private function isRecoveryEvent(string $type): bool
    {
        return Str::contains($type, ['Recovered', 'Succeeded'], ignoreCase: true);
    }

    private function resolveSeverity(string $type): string
    {
        $warningKeywords = [
            'NotExecutedOnTime',
            'ExpiresSoon',
            'BrokenLinks',
            'MixedContent',
            'Performance',
            'Lighthouse',
            'Sitemap',
            'DnsHistory',
        ];

        if (Str::contains($type, $warningKeywords, ignoreCase: true)) {
            return 'warning';
        }

        return 'error';
    }

    private function buildTitle(string $type, array $payload): string
    {
        $eventName = Str::of($type)
            ->replaceLast('Notification', '')
            ->snake(' ')
            ->ucfirst()
            ->toString();

        $summary = Arr::get($payload, 'run.latest_completed_run_summary')
            ?? Arr::get($payload, 'run.check.latest_completed_run_summary');

        return $summary ? "{$eventName}: {$summary}" : $eventName;
    }
}
