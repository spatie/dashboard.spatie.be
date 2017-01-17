@extends('layouts/master')

@section('content')

    @javascript(compact('pusherKey'))

    <div class="dashboard" id="dashboard">
            <twitter grid="a1:a3"></twitter>

            <google-calendar grid="b1:b2"></google-calendar>

            <last-fm grid="c1:d1"></last-fm>

            <current-time grid="e1" dateformat="ddd DD/MM"></current-time>

            <packagist-statistics grid="e2"></packagist-statistics>

            <rain-forecast grid="c2"></rain-forecast>

            <internet-connection grid="d2"></internet-connection>

            <github-file file-name="freek" grid="e3"></github-file>
            <github-file file-name="rogier" grid="b3"></github-file>
            <github-file file-name="seb" grid="c3"></github-file>
            <github-file file-name="willem" grid="d3"></github-file>
    </div>

@endsection
