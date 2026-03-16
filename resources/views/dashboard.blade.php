@push('assets')
    @vite('resources/css/app.css')
@endpush
<x-dashboard>
    {{--
    <livewire:twitter-tile position="a1:a18" />
    --}}
    <livewire:belgian-trains-tile position="a1:a14"/>

    <livewire:velo-tile position="a15:a20" />

    @foreach ($members->split(2) as $groupIndex => $group)
        @php($column = $groupIndex > 0 ? 'c' : 'b')
        @php($row = 0)

        @foreach ($group as $memberIndex => $member)
            <livewire:team-member-tile
                position="{{ $column }}{{ ++$row }}:{{ $column }}{{ ++$row }}"
                name="{{ strtolower($member['name']) }}"
                :avatar="gravatar($member['email'])"
                :birthday="$member['birthday']"
            />
        @endforeach
    @endforeach

    <livewire:calendar-tile position="e7:e20" :calendar-id="config('google-calendar.calendar_id')" />

    
    <livewire:fathom-tile position="b1:b6" siteId="GSENXMLW" label="📯 Mailcoach" />
    <livewire:fathom-tile position="c1:C6" siteId="LBABKDJB" label="🎆 Flare" />
    <livewire:statistics-tile position="d1:d6" />

    <livewire:oh-dear-uptime-tile position="e7:e16" />

    <livewire:time-weather-tile position="e1:e6" />
</x-dashboard>
