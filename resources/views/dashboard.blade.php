@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))
<div id="dashboard">
    <dashboard class="font-sans">
        <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a12"></twitter>
        <uptime position="a1:a12"></uptime>
        <team-member name="adriaan" avatar="{{ gravatar('adriaan@spatie.be') }}" birthday="1995-10-22" position="b1:b4"></team-member>
        <team-member name="alex" avatar="{{ gravatar('alex@spatie.be') }}" birthday="1996-02-05" position="c1:c4"></team-member>
        <team-member name="brent" avatar="{{ gravatar('brent@spatie.be') }}" birthday="1994-07-30" position="b5:b8"></team-member>
        <team-member name="freek" avatar="{{ gravatar('freek@spatie.be') }}" birthday="1979-09-22" position="c5:c8"></team-member>
        <team-member name="ruben" avatar="{{ gravatar('ruben@spatie.be') }}" birthday="1994-05-16" position="b9:b12"></team-member>
        <team-member name="seb" avatar="{{ gravatar('sebastian@spatie.be') }}" birthday="1992-02-01" position="c9:c12"></team-member>
        <team-member name="willem" avatar="{{ gravatar('willem@spatie.be') }}" birthday="1975-09-04" position="d9:d12"></team-member>
        <team-member name="jef" avatar="{{ gravatar('jef@spatie.be') }}" birthday="1975-03-28" position="d7"></team-member>
        <team-member name="wouter" avatar="{{ gravatar('wouter@spatie.be') }}" birthday="1991-03-15" position="d8"></team-member>
        <statistics position="d1:d6"></statistics>
        <time-weather position="e1:e3" date-format="ddd DD/MM" time-zone="Europe/Brussels" weather-city="Antwerp"></time-weather>
        <calendar position="e4:e9"></calendar>
        <velo position="e10:e12"></velo>
        <internet-connection position="e1:e3"></internet-connection>
    </dashboard>
</div>

@endsection
