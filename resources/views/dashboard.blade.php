@extends('layouts/master')

@section('content')

    <x-dashboard>
        <livewire:team-member
            name="adriaan"
            avatar="{{ gravatar('adriaan@spatie.be') }}"
            birthday="1995-10-22"
            position="b1:b8"
        />
    </x-dashboard>

@endsection
