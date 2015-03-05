{{-- Needs variables: text --}}

@extends('layouts.master')

@section('title')
        Es ist ein Fehler aufgetreten.
@stop

@section('content')
        
        <div class="panel">{{ $text }}</div>
@stop