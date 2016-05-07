

<div class="form-group">
    {!! Form::label('title', 'Umfragentitel:') !!}
    {!! Form::text('title', null, ['placeholder'=>'z.B. Teilnahme an der Clubfahrt',
        'required',
        'class' => 'form-control'
        ]) !!}
</div>
<div class="form-group">
    {!! Form::label('description', 'Umfragenbeschreibung:') !!}
    {!! Form::textarea('description', null, ['size' => '100x4',
        'class' => 'form-control'
        ]) !!}
</div>
<div class="form-group">
    {!! Form::label('deadline', 'Umfrage aktiv bis:') !!}
    {!! Form::date('deadline', $date, ['class' => 'form-control']) !!}
</div>

@include('partials.surveyField')
@include('partials.surveyField')

<div class="form-group">
    {!! Form::submit($submitButtonText, ['class'=>'btn btn-primary']) !!}
    &nbsp;&nbsp;&nbsp;&nbsp;
    <br class="visible-xs">
    <a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
</div>