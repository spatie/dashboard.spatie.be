<?php

namespace App\Services\OhDearWebhooks;

use Illuminate\Events\Dispatcher;

class EventSubscriber
{
    public function onUptimeCheckFailed($event) {
        \Log::info('get event on onUptimeCheckFailed');
        \Log::info(print_r($event, true));
    }

    public function onUptimeCheckRecovered($event) {
        \Log::info('get event on onUptimeCheckFailed');
        \Log::info(print_r($event, true));
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'ohdear-webhooks::uptimeCheckFailed',
            'App\Services\OhDearWebhooks\EventSubscribers@onUptimeCheckFailed'
        );

        $events->listen(
            'ohdear-webhooks::uptimeCheckRestored',
            'App\Services\OhDearWebhooks\EventSubscribers@onUptimeCheckRestored'
        );
    }

}