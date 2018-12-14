@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'clientConnectionPath'))
<div id="dashboard">
    <dashboard class="font-sans" :members="{{ json_encode($members) }}">
        @php  
        $cols = ['a', 'b', 'c', 'd'];
        $flags = ['a' => 1, 'b' => 1, 'c' => 1, 'd' => 1];
        @endphp
        @foreach($members as $k => $mem)
            @php  
                $col = $cols[$k%4];
                $size = $mem['isDev'] ? 5 : 4;
                $rowStart = $flags[$col];
                $rowEnd = $rowStart + $size - 1;
                $flags[$col] = $flags[$col] + $size;
            @endphp
            
            <team-member :info="{{ json_encode($mem) }}" :position="'{{$col.$rowStart}}:{{$col.$rowEnd}}'"></team-member>
        @endforeach

        <time-weather position="e1:e6" date-format="ddd DD/MM" time-zone="Europe/Brussels" weather-city="Antwerp"></time-weather>
        <internet-connection position="e1:e6"></internet-connection>
        <uptime position="d1:d10"></uptime>
        <calendar position="e7:e16"></calendar>
        <party position="e17:e24"></party>
        {{-- <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a24"></twitter> --}}
        {{-- <tile-timer on="16:00" off="19:00">
            <trains position="a1:a24"></trains>
        </tile-timer> --}}
        {{-- <velo position="e17:e24"></velo> --}}
        {{-- <statistics position="d1:d10"></statistics> --}}
    </dashboard>
</div>

@endsection
