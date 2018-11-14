@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

<div id="dashboard" class="fixed pin grid gap-spacing w-screen h-screen p-spacing font-sans font-normal leading-normal text-default bg-screen">
    <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a12"></twitter>
    <uptime position="a1:a12"></uptime>
    <packagist position="b1:b4"></packagist>
    <npm position="b5:b8"></npm>
    <github position="b9:b12"></github>
    <team-member name="adriaan" avatar="{{ gravatar('adriaan@spatie.be') }}" position="c1:c3"></team-member>
    <team-member name="alex" avatar="{{ gravatar('alex@spatie.be') }}" position="c4:c6"></team-member>
    <team-member name="brent" avatar="{{ gravatar('brent@spatie.be') }}" position="c7:c9"></team-member>
    <team-member name="freek" avatar="{{ gravatar('freek@spatie.be') }}" position="c10:c12"></team-member>
    <team-member name="ruben" avatar="{{ gravatar('ruben@spatie.be') }}" position="d1:d3"></team-member>
    <team-member name="seb" avatar="{{ gravatar('sebastian@spatie.be') }}" position="d4:d6"></team-member>
    <team-member name="willem" avatar="{{ gravatar('willem@spatie.be') }}" position="d7:d9"></team-member>
    <velo position="d10:d12"></velo>
    <time-weather position="e1:e3" date-format="ddd DD/MM" time-zone="Europe/Brussels" weather-city="Antwerp"></time-weather>
    <calendar position="e4:e12"></calendar>
    <internet-connection></internet-connection>
</div>

@endsection
