@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

    <div class="dashboard" id="dashboard">
        <twitter :initial-tweets="{{ json_encode($initialTweets) }}" grid="a1:a3"></twitter>
        <calendar grid="b1:b2"></calendar>
        <music grid="c1:d1"></music>
        <uptime grid="b3"></uptime>

        <tasks team-member="freek" grid="c2"></tasks>
        <tasks team-member="alex" grid="d2"></tasks>
        <tasks team-member="seb" grid="c3"></tasks>
        <tasks team-member="willem" grid="d3"></tasks>

        <time-weather grid="e1" dateformat="ddd DD/MM"></time-weather>
        <packagist grid="e2"></packagist>
        <github grid="e3"></github>

        <internet-connection></internet-connection>
    </div>
@endsection
