

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
    {!! Form::date('deadline', $time, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('in_calendar', 'In Kalender am:') !!}
    {!! Form::date('in_calendar', $date, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <label class="label_checkboxitem" for="checkboxitemitem"></label>
        <label><input type="checkbox" id="required1" value="required1" name="is_private" class="input_checkboxitem"> is private</label>
    <label class="label_checkboxitem" for="checkboxitemitem"></label>
        <label><input type="checkbox" id="required2" value="required2" name="is_anonymous" class="input_checkboxitem"> is anonymous</label>
    <label class="label_checkboxitem" for="checkboxitemitem"></label>
        <label><input type="checkbox" id="required3" value="required3" name="show_results_after_voting" class="input_checkboxitem"> show results after voting</label>
    </div>
<hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">

@include('partials.surveyField')