<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.form-group').change(function() {
            $(window).bind('beforeunload', function() {
                return 'Beim Verlassen der Seite gehen alle Eingaben verloren.';
            });
        });
        $("form").submit(function() {
            $(window).unbind('beforeunload');
        });
    });
</script>

<div class="form-group">
    {!! Form::label('title', 'Umfragentitel:') !!}
    {!! Form::text('title', $survey->title, ['placeholder'=>'z.B. Teilnahme an der Clubfahrt',
        'required',
        'class' => 'form-control'
        ]) !!}
</div>
<div class="form-group">
    {!! Form::label('description', 'Umfragenbeschreibung:') !!}
    {!! Form::textarea('description', $survey->description, ['size' => '100x4',
        'class' => 'form-control'
        ]) !!}
</div>
<div class="form-group">
    {!! Form::label('deadline', 'Umfrage aktiv bis:') !!}
    {!! Form::date('deadline', $time, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <div>
        <label class="label_checkboxitem" for="checkboxitemitem"></label>
        <label><input type="checkbox" id="required1" value="required1" name="is_private" class="input_checkboxitem"
                      @if($survey->is_private) checked @endif> Nur für eingeloggte Nutzer sichtbar?  </label>
    </div>
    <div>
        <label class="label_checkboxitem" for="checkboxitemitem"></label>
        <label><input type="checkbox" id="required2" value="required2" name="is_anonymous" class="input_checkboxitem"
                      @if($survey->is_anonymous) checked @endif> anonyme Umfrage</label>
    </div>
    <div>
        <label class="label_checkboxitem" for="checkboxitemitem"></label>
        <label><input type="checkbox" id="required3" value="required3" name="show_results_after_voting" class="input_checkboxitem"
                      @if($survey->show_results_after_voting) checked @endif> zeige Ergebnisse nach der Abstimmung</label>
    </div>
</div>

<div class="form-group">
    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
        <label for="password" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort zum Eintragen:</label>
        <div class="col-md-7 col-sm-7 col-xs-12">
            {!! Form::password('password', '' ) !!}
        </div>
    </div>

    <div class="form-groupcol-md-12 col-sm-12 col-xs-12 no-padding">
        <label fro="passwordDouble" class="control-label col-md-5 col-sm-5 col-xs-12">Passwort wiederholen:</label>
        <div class="col-md-7 col-sm-7 col-xs-12">
            {!! Form::password('password_confirmation', '') !!}
        </div>
    </div>
</div>
@if (!empty($survey->password))
    <div style="color: #ff9800;">
        <small>Um das Passwort zu löschen, trage in beide Felder "delete" ein (ohne
            Anführungszeichen).
        </small>
    </div>
@endif

<hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">

@include('partials.surveyField')
