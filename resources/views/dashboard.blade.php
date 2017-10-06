@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

<dashboard id="dashboard" columns="2" rows="3">
    {{--<uptime position="a1:a3"></uptime>--}}
    <github-project position="a1" project="SOL2"></github-project>
    <github-project position="a2" project="SOL"></github-project>
    <github-project position="a3" project="SOL-management"></github-project>
    <time-weather position="b1" date-format="DD/MM/YYYY" time-format="HH:mm:ss" time-zone="Europe/Brussels" weather-city="Gent"></time-weather>
    <internet-connection></internet-connection>
</dashboard>

@endsection
