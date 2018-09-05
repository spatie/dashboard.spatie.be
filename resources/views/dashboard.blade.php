@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

<dashboard id="dashboard" columns="6" rows="3">
    <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a3"></twitter>
    <uptime position="a1:a3"></uptime>
    <packagist position="b1"></packagist>
    <npm position="b2"></npm>
    <github position="b3"></github>
    <music position="d1:e1"></music>
    <tasks team-member="adriaan" position="d2"></tasks>
    <tasks team-member="alex" position="e2"></tasks>
    <tasks team-member="brent" position="f2"></tasks>
    <tasks team-member="freek" position="c3"></tasks>
    <tasks team-member="ruben" position="d3"></tasks>
    <tasks team-member="seb" position="e3"></tasks>
    <tasks team-member="willem" position="f3"></tasks>
    <time-weather position="f1" date-format="ddd DD/MM" time-zone="Europe/Brussels" weather-city="Antwerp"></time-weather>
    <calendar position="c1:c2"></calendar>
    <internet-connection></internet-connection>
</dashboard>

@endsection
