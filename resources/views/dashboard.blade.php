@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey', 'pusherCluster', 'usingNodeServer'))

    <div class="dashboard" id="dashboard">
        <twitter :initial-tweets="{{ json_encode($initialTweets) }}" grid="a1:a3"></twitter>
        <google-calendar grid="b1:b2"></google-calendar>
        <last-fm grid="c1:d1"></last-fm>
        <rain-forecast grid="c2"></rain-forecast>
        <uptime-monitor grid="d2"></uptime-monitor>
        <github-file file-name="freek" grid="b3"></github-file>
        <github-file file-name="seb" grid="c3"></github-file>
        <github-file file-name="willem" grid="d3"></github-file>
        <current-time grid="e1" dateformat="ddd DD/MM"></current-time>
        <packagist-statistics grid="e2"></packagist-statistics>
        <github-statistics grid="e3"></github-statistics>

        <internet-connection></internet-connection>
    </div>
@endsection
