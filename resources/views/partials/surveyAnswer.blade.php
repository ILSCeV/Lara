<div class="row">
    <form method="POST" action="/survey/{{ $survey->id }}/answer">
    <div class="panel padding-left-minimal">
        <h5 class="panel-title text-center">Antwort</h5>
            <div class="form-group">
                <h6>Hier Antwort eingeben:</h6>
                <textarea name="answer[]" class="form-control"></textarea>
                <textarea name="answer[]" class="form-control"></textarea>
            </div>
    </div>
    <button type="submit" class="btn btn-primary">Abstimmen</button>
    <a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </form>
</div>
