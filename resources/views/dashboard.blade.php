@push('assets')
    @vite('resources/css/app.css')
@endpush
@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('deploy-detected', () => {
            window.location.reload();
        });
    });
</script>
@endpush
<x-dashboard>
    <livewire:deploy-checker />
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

    <livewire:fathom-tile position="b1:b8" siteId="GSENXMLW" label="📯 Mailcoach" />
    <livewire:fathom-tile position="c1:c8" siteId="LBABKDJB" label="🎆 Flare" />
    <livewire:fathom-tile position="d1:d8" siteId="OMNDKUTR" label="🔵 Spatie" />
    <livewire:statistics-tile position="b9:b13" />

    <livewire:oh-dear-uptime-tile position="e7:e16" />

    <livewire:time-weather-tile position="e1:e6" />

    <livewire:now-playing-tile position="d9:d16" />
</x-dashboard>
