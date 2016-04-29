@extends('layouts.master')

@section('title')
    Neue Umfrage erstellen
@stop

@section('content')
    <h4>Neue Umfrage erstellen:</h4>
    <div class="form-group">
        {!! Form::label('title', 'Umfragentitel:') !!}
        {!! Form::text('title', null, array('placeholder'=>'z.B. Teilnahme an der Clubfahrt',
                                            'required'
                                            )) !!}
    </div>
    <div class="form-group">
        {!! Form::label('description', 'Umfragenbeschreibung:') !!}
        {!! Form::textarea('description', null) !!}
    </div>
    <div>
    {!! Form::submit('Umfrage erstellen') !!}
    </div>

@stop