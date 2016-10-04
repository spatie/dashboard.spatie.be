@extends('layouts/master')

@section('content')
    @javascript(compact('pusherKey'))
    <div id="dashboard"></div>
@endsection
