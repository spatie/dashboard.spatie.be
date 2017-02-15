@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

    <div class="dashboard" id="dashboard">
        <twitter :initial-tweets="{{ json_encode($initialTweets) }}" grid="a1:a3"></twitter>
        <google-calendar grid="b1:b2"></google-calendar>
        <rain-forecast grid="b3"></rain-forecast>
        <github-file file-name="freek" grid="c1"></github-file>
        <github-file file-name="seb" grid="d1"></github-file>
        <github-file file-name="rogier" grid="c2"></github-file>
        <github-file file-name="willem" grid="d2"></github-file>
        <uptime-monitor grid="c3"></uptime-monitor>
        <last-fm grid="d3:e3"></last-fm>
        <current-time grid="e1" dateformat="ddd DD/MM"></current-time>
        <packagist-statistics grid="e2"></packagist-statistics>

        <internet-connection></internet-connection>
    </div>
@endsection
