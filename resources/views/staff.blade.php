@extends('layouts/master')

@section('content')

@javascript(compact('pusherKey', 'clientConnectionPath'))
<div id="dashboard">
<staff :members="{{ json_encode($members) }}" :archived="{{ json_encode($archived) }}" />
</div>

@endsection
