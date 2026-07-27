@php($chart = $this->chart())

<div class="contents" data-product-analytics-component="{{ $screenName }}">
    <x-dashboard-tile position="a1:c2" :fade="false">
        <div class="flex h-full items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-3xl">{{ $emoji }}</span>
                <div>
                    <h1 class="text-2xl font-black tracking-tight">{{ $productName }}</h1>
                    <p class="text-sm text-dimmed">Product analytics</p>
                </div>
            </div>
            <div class="text-right text-xs text-dimmed">
                <div>Today · Europe/Brussels</div>
                <div>{{ now('Europe/Brussels')->format('D d M, H:i') }}</div>
            </div>
        </div>
    </x-dashboard-tile>

    <x-dashboard-tile position="a3:c12" :fade="false">
        <div class="flex h-full flex-col">
            <div class="flex items-baseline justify-between">
                <div>
                    <h2 class="font-bold">Daily visitors</h2>
                    <p class="text-xs text-dimmed">Last 30 days · visits</p>
                </div>
                @if($daily)
                    @php($comparison = $daily['forecast']['comparison_percent'])
                    <p class="text-sm text-dimmed">
                        Typical {{ now('Europe/Brussels')->format('l') }}:
                        <span class="font-bold tabular-nums text-default">{{ number_format($daily['forecast']['typical']) }}</span>
                        @if($comparison !== null)
                            · {{ abs($comparison) }}% {{ $comparison >= 0 ? 'above' : 'below' }}
                        @endif
                    </p>
                @endif
            </div>

            <div class="min-h-0 grow">
                @if($daily)
                    <svg
                        class="h-full w-full overflow-visible"
                        viewBox="0 0 {{ $chart['width'] }} {{ $chart['height'] }}"
                        role="img"
                        aria-label="Daily visitors for the last 30 days"
                    >
                        <g class="text-dimmed" fill="none" stroke="currentColor" stroke-opacity=".18">
                            <line x1="{{ $chart['left'] }}" y1="{{ $chart['top'] }}" x2="{{ $chart['right_x'] }}" y2="{{ $chart['top'] }}" />
                            <line x1="{{ $chart['left'] }}" y1="{{ $chart['middle_y'] }}" x2="{{ $chart['right_x'] }}" y2="{{ $chart['middle_y'] }}" />
                            <line x1="{{ $chart['left'] }}" y1="{{ $chart['bottom_y'] }}" x2="{{ $chart['right_x'] }}" y2="{{ $chart['bottom_y'] }}" />
                        </g>
                        <g class="fill-dimmed text-[11px] tabular-nums">
                            <text x="{{ $chart['left'] - 8 }}" y="{{ $chart['top'] + 4 }}" text-anchor="end">{{ number_format($chart['max']) }}</text>
                            <text x="{{ $chart['left'] - 8 }}" y="{{ $chart['middle_y'] + 4 }}" text-anchor="end">{{ number_format($chart['middle']) }}</text>
                            <text x="{{ $chart['left'] - 8 }}" y="{{ $chart['bottom_y'] + 4 }}" text-anchor="end">0</text>
                            <text x="{{ $chart['left'] }}" y="{{ $chart['height'] - 8 }}">{{ \Carbon\CarbonImmutable::parse($chart['first_label'])->format('d M') }}</text>
                            <text x="{{ ($chart['left'] + $chart['right_x']) / 2 }}" y="{{ $chart['height'] - 8 }}" text-anchor="middle">{{ \Carbon\CarbonImmutable::parse($chart['middle_label'])->format('d M') }}</text>
                            <text x="{{ $chart['right_x'] }}" y="{{ $chart['height'] - 8 }}" text-anchor="end">Today</text>
                        </g>
                        <line
                            x1="{{ $chart['marker_x'] }}"
                            y1="{{ $chart['top'] }}"
                            x2="{{ $chart['marker_x'] }}"
                            y2="{{ $chart['bottom_y'] }}"
                            class="text-accent"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-dasharray="5 5"
                            opacity=".75"
                        />
                        <polyline
                            points="{{ $chart['points'] }}"
                            class="text-accent"
                            fill="none"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="4"
                        />
                        <circle cx="{{ $chart['marker_x'] }}" cy="{{ $chart['forecast_y'] }}" r="4" class="fill-accent" />
                        <text
                            x="{{ $chart['marker_x'] - 10 }}"
                            y="{{ $chart['forecast_label_y'] }}"
                            text-anchor="end"
                            class="fill-default text-[12px] font-bold tabular-nums"
                        >Forecast {{ number_format($daily['forecast']['value']) }}</text>
                    </svg>
                @elseif($unavailable['daily'])
                    <div class="flex h-full items-center justify-center text-sm text-dimmed">Analytics unavailable</div>
                @else
                    <div class="flex h-full items-center justify-center text-sm text-dimmed">Loading analytics…</div>
                @endif
            </div>

            @if($daily)
                <p class="text-right text-[10px] text-dimmed">Updated {{ \Carbon\CarbonImmutable::parse($daily['updated_at'])->format('H:i:s') }}</p>
            @endif
        </div>
    </x-dashboard-tile>

    <x-dashboard-tile position="a13:a16" :fade="false">
        <h2 class="font-bold">Today</h2>
        @if($daily)
            <div class="mt-3 grid grid-cols-2 gap-4">
                <div>
                    <div class="text-3xl font-black tabular-nums">{{ number_format($daily['today']['pageviews']) }}</div>
                    <div class="text-sm text-dimmed">Pageviews</div>
                </div>
                <div>
                    <div class="text-3xl font-black tabular-nums">{{ number_format($daily['today']['views_per_visit'], 2) }}</div>
                    <div class="text-sm text-dimmed">Views / visit</div>
                </div>
            </div>
        @elseif($unavailable['daily'])
            <p class="mt-4 text-sm text-dimmed">Today’s metrics unavailable</p>
        @else
            <p class="mt-4 text-sm text-dimmed">Loading…</p>
        @endif
    </x-dashboard-tile>

    <x-dashboard-tile position="a17:a20" :fade="false">
        <h2 class="font-bold">Engagement</h2>
        @if($daily)
            <div class="mt-3 grid grid-cols-2 gap-4">
                <div>
                    <div class="text-3xl font-black tabular-nums">{{ number_format($daily['today']['bounce_rate'], 1) }}%</div>
                    <div class="text-sm text-dimmed">Bounce rate</div>
                </div>
                <div>
                    <div class="text-3xl font-black tabular-nums">{{ $this->duration($daily['today']['avg_duration']) }}</div>
                    <div class="text-sm text-dimmed">Avg visit</div>
                </div>
            </div>
            <p class="absolute bottom-3 right-4 text-[10px] text-dimmed">Updated {{ \Carbon\CarbonImmutable::parse($daily['updated_at'])->format('H:i:s') }}</p>
        @elseif($unavailable['daily'])
            <p class="mt-4 text-sm text-dimmed">Engagement unavailable</p>
        @else
            <p class="mt-4 text-sm text-dimmed">Loading…</p>
        @endif
    </x-dashboard-tile>

    <x-dashboard-tile position="b13:b20" :fade="false">
        <div class="flex items-baseline justify-between">
            <h2 class="font-bold">Live visitors</h2>
            @if($live)
                <div class="text-3xl font-black tabular-nums">{{ number_format($live['total']) }}</div>
            @endif
        </div>

        @if($live)
            <ol class="mt-3 divide-y divide-default/10">
                @forelse($live['pages'] as $page)
                    <li class="flex items-center justify-between gap-3 py-1.5">
                        <span class="truncate text-sm">{{ $page['label'] }}</span>
                        <span class="font-bold tabular-nums">{{ number_format($page['value']) }}</span>
                    </li>
                @empty
                    <li class="py-4 text-sm text-dimmed">No one is browsing right now.</li>
                @endforelse
            </ol>
            <p class="absolute bottom-3 right-4 text-[10px] text-dimmed">Updated {{ \Carbon\CarbonImmutable::parse($live['updated_at'])->format('H:i:s') }}</p>
        @elseif($unavailable['live'])
            <p class="mt-4 text-sm text-dimmed">Live visitors unavailable</p>
        @else
            <p class="mt-4 text-sm text-dimmed">Loading…</p>
        @endif
    </x-dashboard-tile>

    <x-dashboard-tile position="c13:c16" :fade="false">
        <div class="flex items-baseline justify-between gap-2">
            <h2 class="font-bold">Top pages today</h2>
            @if($pages)
                <p class="shrink-0 text-[10px] text-dimmed">Updated {{ \Carbon\CarbonImmutable::parse($pages['updated_at'])->format('H:i:s') }}</p>
            @endif
        </div>
        @if($pages)
            <ol class="mt-1 divide-y divide-default/10">
                @forelse($pages['rows'] as $page)
                    <li class="flex items-center justify-between gap-3 py-0.5">
                        <span class="truncate text-sm">{{ $page['label'] }}</span>
                        <span class="font-bold tabular-nums">{{ number_format($page['value']) }}</span>
                    </li>
                @empty
                    <li class="py-2 text-sm text-dimmed">No pages yet today.</li>
                @endforelse
            </ol>
        @elseif($unavailable['pages'])
            <p class="mt-4 text-sm text-dimmed">Top pages unavailable</p>
        @else
            <p class="mt-4 text-sm text-dimmed">Loading…</p>
        @endif
    </x-dashboard-tile>

    <x-dashboard-tile position="c17:c20" :fade="false">
        <div class="flex items-baseline justify-between gap-2">
            <h2 class="font-bold">Traffic sources today</h2>
            @if($sources)
                <p class="shrink-0 text-[10px] text-dimmed">Updated {{ \Carbon\CarbonImmutable::parse($sources['updated_at'])->format('H:i:s') }}</p>
            @endif
        </div>
        @if($sources)
            <ol class="mt-1 divide-y divide-default/10">
                @forelse($sources['rows'] as $source)
                    <li class="flex items-center justify-between gap-3 py-0.5">
                        <span class="truncate text-sm">{{ $source['label'] }}</span>
                        <span class="font-bold tabular-nums">{{ number_format($source['value']) }}</span>
                    </li>
                @empty
                    <li class="py-2 text-sm text-dimmed">No sources yet today.</li>
                @endforelse
            </ol>
        @elseif($unavailable['sources'])
            <p class="mt-4 text-sm text-dimmed">Traffic sources unavailable</p>
        @else
            <p class="mt-4 text-sm text-dimmed">Loading…</p>
        @endif
    </x-dashboard-tile>
</div>
