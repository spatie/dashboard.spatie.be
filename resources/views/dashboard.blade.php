@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

<dashboard id="dashboard" columns="5" rows="3">
    <twitter :initial-tweets="{{ json_encode($initialTweets) }}" position="a1:a3"></twitter>
    <calendar position="b1:b2"></calendar>
    <music position="c1:d1"></music>
    <uptime position="b3"></uptime>

    <tasks team-member="alex" position="c2"></tasks>
    <tasks team-member="freek" position="d2"></tasks>
    <tasks team-member="seb" position="c3"></tasks>
    <tasks team-member="willem" position="d3"></tasks>

    <time-weather position="e1" date-format="ddd DD/MM"></time-weather>
    <packagist position="e2"></packagist>
    <github position="e3"></github>

    <internet-connection></internet-connection>
</dashboard>

@endsection
