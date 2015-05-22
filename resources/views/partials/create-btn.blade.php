<div class="btn-group">
    @if(Session::has('userGroup')
        AND (Session::get('userGroup') == 'marketing'
        OR Session::get('userGroup') == 'clubleitung'))
        <a href="{{ Request::getBasePath() }}/calendar/create" 
           class="btn btn-primary pull-left hidden-print">
                Neue Veranstaltung erstellen
        </a>
    @else
        &nbsp;
    @endif
</div>
