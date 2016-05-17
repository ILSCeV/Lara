@extends('layouts.master')

@section('title')
    Umfrage
@stop

@section('content')
    <div class="btn-group col-md-6">
        <div class="row">
            <div class="panel no-padding">
                <h4 class="panel-title text-center">Sie haben erfolgreich an der Umfrage teilgenommen</h4>
                <div class="panel-body">
                    <h6>Vielen Dank</h6>
                    &nbsp;
                    <h6>Ihre Antwort war:</h6>
                    <h5 class="bg-success text-center"> </h5>
                    </div>
                </div>
            </div>
        </div>

{{--
   <div>
       @include('partials.surveyEvaluation')
    </div>
    --}}

@stop