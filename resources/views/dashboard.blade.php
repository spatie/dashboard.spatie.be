@extends('layouts/master')

@section('content')
@javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

<dashboard id="dashboard" columns="5" rows="12">
    <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a12"></twitter>
    <!--
    <uptime position="a1:a12"></uptime>
    <packagist position="b1:b4"></packagist>
    <npm position="b5:b8"></npm>
    <github position="b9:b12"></github>
    <music position="d10:d12"></music>
    <tasks team-member="joca" position="c7:c9"></tasks>
    <tasks team-member="miguelito" position="c10:c12"></tasks>
    <tasks team-member="willem" position="d7:d9"></tasks>
    -->
    <time-weather position="c1:c3" date-format="ddd DD/MM" time-zone="America/Lima" weather-city="Lima"></time-weather>
    <time-weather position="c4:c6" date-format="ddd DD/MM" time-zone="America/Los_Angeles" weather-city="San Francisco"></time-weather>
    <time-weather position="d1:d3" date-format="ddd DD/MM" time-zone="America/Mexico_City" weather-city="Mexico City"></time-weather>
    <time-weather position="d4:d6" date-format="ddd DD/MM" time-zone="America/New_York" weather-city="Charlotte"></time-weather>
    <calendar calendar-summary="Ontime" position="e1:e6"></calendar>
    <calendar calendar-summary="TK Birthdays" position="b1:b6"></calendar>
    <internet-connection></internet-connection>
</dashboard>

@endsection
