<x-dashboard>
    <livewire:twitter-tile position="a1:a24" />

    <livewire:team-member-tile
        position="b1:b4"
        name="adriaan"
        :avatar="gravatar('adriaan@spatie.be')"
        birthday="1995-10-22"
    />

    <livewire:team-member-tile
        position="c1:c4"
        name="alex"
        :avatar="gravatar('alex@spatie.be')"
        birthday="1996-02-05"
    />

    <livewire:team-member-tile
        name="brent"
        :avatar="gravatar('brent@spatie.be')"
        birthday="1994-07-30"
        position="d1:d4"
    />

    <livewire:team-member-tile
        name="freek"
        :avatar="gravatar('freek@spatie.be')"
        birthday="1979-09-22"
        position="b5:b8"
    />

    <livewire:team-member-tile
        name="rias"
        :avatar="gravatar('rias@spatie.be')"
        birthday="1992-05-25"
        position="c5:c8"
    />

    <livewire:team-member-tile
        name="ruben"
        :avatar="gravatar('ruben@spatie.be')"
        birthday="1994-05-16"
        position="d5:d8"
    />

    <livewire:team-member-tile
        name="sebastian"
        display-name="seb"
        :avatar="gravatar('sebastian@spatie.be')"
        birthday="1992-02-01"
        position="b9:b12"
        nickName="seb"
    />

    <livewire:team-member-tile
        name="willem"
        :avatar="gravatar('willem@spatie.be')"
        birthday="1975-09-03"
        position="c9:c12"
    />

    <livewire:team-member-tile
        name="jef"
        :avatar="gravatar('jef@spatie.be')"
        birthday="1975-03-28"
        position="d9:d12"
    />

    <livewire:team-member-tile
        name="niels"
        :avatar="gravatar('niels@spatie.be')"
        birthday="1993-07-14"
        position="b13:b16"
    />


    <livewire:team-member-tile
        name="vic"
        :avatar="gravatar('vic@spatie.be')"
        birthday="1994-04-08"
        position="c13:c16"
    />

    <livewire:team-member-tile
        name="wouter"
        :avatar="gravatar('wouter@spatie.be')"
        birthday="1991-03-15"
        position="d13:d16"
    />


    <livewire:calendar-tile position="e7:e16" :calendar-id="config('google-calendar.calendar_id')" />

    <livewire:velo-tile position="e17:e24" />

    <livewire:statistics-tile position="b17:b24" />

    <livewire:coffee-tile position="c17:c24" total-offset="20247" />

    <livewire:attendances-tile position="d17:d24" />

    <livewire:belgian-trains-tile position="a1:a24"/>

    <livewire:oh-dear-uptime-tile position="e7:e16" />

    <livewire:time-weather-tile position="e1:e6" />
</x-dashboard>
