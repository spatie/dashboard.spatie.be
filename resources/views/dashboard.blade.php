@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey'))

    <div class="dashboard" id="dashboard">
            <google-calendar grid="a1:a2"></google-calendar>

            <twitter grid="e1:e4"></twitter>

            <last-fm grid="b1:c1"></last-fm>

            <current-time grid="d1" dateformat="ddd DD/MM"></current-time>

            <packagist-statistics grid="b2"></packagist-statistics>

            <rain-forecast grid="c2"></rain-forecast>

            <internet-connection grid="d2"></internet-connection>

            <github-file file-name="freek" grid="a3"></github-file>
            <github-file file-name="rogier" grid="b3"></github-file>
            <github-file file-name="seb" grid="c3"></github-file>
            <github-file file-name="willem" grid="d3"></github-file>
    </div>

@endsection
