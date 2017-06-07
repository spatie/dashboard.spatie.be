@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

    <div class="dashboard" id="dashboard">
        <twitter :initial-tweets="{{ json_encode($initialTweets) }}" grid="a1:a3"></twitter>
        <calendar grid="b1:b2"></calendar>
        <music grid="c1:d1"></music>
        <uptime grid="d2"></uptime>
        <tasks file-name="freek" grid="b3"></tasks>
        <tasks file-name="seb" grid="c3"></tasks>
        <tasks file-name="willem" grid="d3"></tasks>
        <time grid="e1" dateformat="ddd DD/MM"></time>
        <packagist grid="e2"></packagist>
        <github grid="e3"></github>

        <internet-connection></internet-connection>
    </div>
@endsection
