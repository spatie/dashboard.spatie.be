@extends('layouts/master')

@section('content')



    <last-fm grid="b1:c1"></last-fm>

    <current-time grid="d1" dateformat="ddd DD/MM"></current-time>

    <gitlab-merge-requests grid="a1:a2"></gitlab-merge-requests>

    <packagist-statistics grid="b2:b2"></packagist-statistics>

    <rain-forecast grid="c2:c2"></rain-forecast>

    <internet-connection grid="d2"></internet-connection>

    <tweets grid="a3:b3"></tweets>

@endsection