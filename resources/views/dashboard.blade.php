@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey'))

    <div class="dashboard" id="dashboard">
        <google-calendar grid="a1:a2"></google-calendar>

        <last-fm grid="b1:c1"></last-fm>

        <current-time grid="d1" dateformat="ddd DD/MM"></current-time>

        <packagist-statistics grid="d2"></packagist-statistics>

        <rain-forecast grid="b2"></rain-forecast>

        <internet-connection grid="c2"></internet-connection>

        <github-file file-name="freek" grid="d3"></github-file>
        <github-file file-name="rogier" grid="a3"></github-file>
        <github-file file-name="seb" grid="b3"></github-file>
        <github-file file-name="willem" grid="c3"></github-file>

        <twitter initial-tweets="{{ json_encode($initialTweets) }}" grid="e1:e3"></twitter>
    </div>

@endsection
