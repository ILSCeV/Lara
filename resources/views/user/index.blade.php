
@extends('layouts.master')
@section('title')
    {{ "User Overview" }}
@stop

@section('content')
    <ul>
        @foreach(Lara\User::all() as $user)
            @can('view', $user)
                <li>
                    {{ $user->name }}
                    {{ $user->email }}
                </li>
            @endcan
        @endforeach
    </ul>
@stop
