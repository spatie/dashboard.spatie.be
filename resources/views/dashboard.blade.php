@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

<dashboard id="dashboard" columns="5" rows="12">
    <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a12"></twitter>
    <uptime position="a1:a12"></uptime>
    <packagist position="b1:b4"></packagist>
    <npm position="b5:b8"></npm>
    <github position="b9:b12"></github>
    <tasks team-member="adriaan" position="c1:c3"></tasks>
    <tasks team-member="alex" position="c4:c6"></tasks>
    <tasks team-member="brent" position="c7:c9"></tasks>
    <tasks team-member="freek" position="c10:c12"></tasks>
    <tasks team-member="ruben" position="d1:d3"></tasks>
    <tasks team-member="seb" position="d4:d6"></tasks>
    <tasks team-member="willem" position="d7:d9"></tasks>
    <music position="d10:d12"></music>
    <time-weather position="e1:e4" date-format="ddd DD/MM" time-zone="Europe/Brussels" weather-city="Antwerp"></time-weather>
    <calendar position="e5:e12"></calendar>
    <internet-connection></internet-connection>
</dashboard>

@endsection
