<div class="row">
    <div class="panel padding-left-minimal">
        <h5 class="panel-title text-center">Antwort</h5>
        <form method="POST" action="/survey/{{ $survey->id }}/storeAnswer">

            <div class="form-group">
                <h6>Hier Antwort eingeben:</h6>
                <textarea name="answer" class="form-control"></textarea>
            </div>
            <input name="survey_id" type="hidden" value="{{ $survey->id }}" />
            <div class="form-group">
            </div>
    </div>
    <button type="submit" class="btn btn-primary">Abstimmen</button>
    <a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
</div>
<input type="hidden" name="_token" value="{{ csrf_token() }}" />
</form>
</div>