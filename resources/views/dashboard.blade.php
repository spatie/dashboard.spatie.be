@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

<div id="dashboard" class="fixed pin grid w-screen h-screen p-gap font-sans font-medium leading-normal text-default bg-screen">
    <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a12"></twitter>
    <uptime position="a1:a12"></uptime>
    <packagist position="b1:b4"></packagist>
    <npm position="b5:b8"></npm>
    <github position="b9:b12"></github>
    <tasks team-member="adriaan" avatar="{{ gravatar('adriaan@spatie.be') }}" position="c1:c3"></tasks>
    <tasks team-member="alex" avatar="{{ gravatar('alex@spatie.be') }}" position="c4:c6"></tasks>
    <tasks team-member="brent" avatar="{{ gravatar('brent@spatie.be') }}" position="c7:c9"></tasks>
    <tasks team-member="freek" avatar="{{ gravatar('freek@spatie.be') }}" position="c10:c12"></tasks>
    <tasks team-member="ruben" avatar="{{ gravatar('ruben@spatie.be') }}" position="d1:d3"></tasks>
    <tasks team-member="seb" avatar="{{ gravatar('sebastian@spatie.be') }}" position="d4:d6"></tasks>
    <tasks team-member="willem" avatar="{{ gravatar('willem@spatie.be') }}" position="d7:d9"></tasks>
    <time-weather position="e1:e4" date-format="ddd DD/MM" time-zone="Europe/Brussels" weather-city="Antwerp"></time-weather>
    <calendar position="e5:e12"></calendar>
    <internet-connection></internet-connection>
</div>

@endsection
