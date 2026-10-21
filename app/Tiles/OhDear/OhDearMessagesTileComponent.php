<?php

namespace App\Tiles\OhDear;

use App\Models\OhDearMessage;
use Illuminate\Contracts\View\View;
use Spatie\Dashboard\Components\BaseTileComponent;

class OhDearMessagesTileComponent extends BaseTileComponent
{
    public function render(): View
    {
        $messageGroups = OhDearMessage::query()
            ->where('occurred_at', '>=', now()->subDay())
            ->select('group_key')
            ->selectRaw('MIN(id) as first_id, COUNT(*) as message_count, MAX(occurred_at) as latest_at')
            ->groupBy('group_key');

        $groups = OhDearMessage::query()
            ->joinSub($messageGroups, 'message_groups', 'oh_dear_messages.id', '=', 'message_groups.first_id')
            ->select('severity', 'title', 'site', 'message_groups.message_count', 'message_groups.latest_at')
            ->withCasts(['latest_at' => 'datetime', 'message_count' => 'integer'])
            ->orderByRaw("CASE WHEN severity = 'error' THEN 0 ELSE 1 END")
            ->orderByDesc('message_groups.message_count')
            ->orderBy('message_groups.first_id')
            ->get()
            ->map(fn (OhDearMessage $message) => [
                'severity' => $message->severity,
                'title' => $message->title,
                'site' => $message->site,
                'count' => $message->message_count,
                'latest_at' => $message->latest_at,
            ]);

        return view('components.tiles.ohDearMessages', [
            'groups' => $groups,
        ]);
    }
}
