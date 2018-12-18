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
                $size = 5;
                $rowStart = $flags[$col];
                $rowEnd = $rowStart + $size - 1;
                $flags[$col] = $flags[$col] + $size;

                $leaveInfo = $leaveRequests[$mem['trac']] ?? [];
                $jiraInfo = $jira[$mem['trac']] ?? [];
            @endphp
            
            <team-member 
                :info="{{ json_encode($mem) }}" 
                :position="'{{$col.$rowStart}}:{{$col.$rowEnd}}'"
                :jira="{{ json_encode($jiraInfo) }}"
                :leave="{{ json_encode($leaveInfo) }}"
            ></team-member>
        @endforeach

        <time-weather position="e1:e6" date-format="ddd DD/MM" time-zone="Asia/Ho_Chi_Minh" weather-city="Ha noi"></time-weather>
        <announcement position="e7:e16"></announcement>
        <party position="e17:e24"></party>
        
        {{-- <uptime position="d1:d10"></uptime> --}}
        {{-- <internet-connection position="e1:e6"></internet-connection> --}}
        {{-- <calendar position="e7:e16"></calendar> --}}
        {{-- <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a24"></twitter> --}}
        {{-- <tile-timer on="16:00" off="19:00">
            <trains position="a1:a24"></trains>
        </tile-timer> --}}
        {{-- <velo position="e17:e24"></velo> --}}
        {{-- <statistics position="d1:d10"></statistics> --}}
    </dashboard>
</div>

@endsection
