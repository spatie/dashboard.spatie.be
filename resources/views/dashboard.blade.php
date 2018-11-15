@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

    <dashboard>
        <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a12"></twitter>
        <uptime position="a1:a12"></uptime>
        <team-member name="adriaan" avatar="{{ gravatar('adriaan@spatie.be') }}" position="b1:b4"></team-member>
        <team-member name="alex" avatar="{{ gravatar('alex@spatie.be') }}" position="c1:c4"></team-member>
        <team-member name="brent" avatar="{{ gravatar('brent@spatie.be') }}" position="d1:d4"></team-member>
        <team-member name="freek" avatar="{{ gravatar('freek@spatie.be') }}" position="b5:b8"></team-member>
        <team-member name="ruben" avatar="{{ gravatar('ruben@spatie.be') }}" position="c5:c8"></team-member>
        <team-member name="seb" avatar="{{ gravatar('sebastian@spatie.be') }}" position="b9:b12"></team-member>
        <team-member name="willem" avatar="{{ gravatar('willem@spatie.be') }}" position="c9:c12"></team-member>
        <team-member name="wouter" avatar="{{ gravatar('wouter@spatie.be') }}" position="d11:d12"></team-member>
        <statistics position="d5:d10"></statistics>
        <time-weather position="e1:e3" date-format="ddd DD/MM" time-zone="Europe/Brussels" weather-city="Antwerp"></time-weather>
        <calendar position="e4:e9"></calendar>
        <velo position="e10:e12"></velo>
        <internet-connection></internet-connection>
    </dashboard>

@endsection
