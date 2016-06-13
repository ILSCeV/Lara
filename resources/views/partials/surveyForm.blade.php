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
<hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">

@include('partials.surveyField')
