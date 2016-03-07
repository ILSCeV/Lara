<div class="btn-group">
    @if(Session::has('userGroup')
        AND (Session::get('userGroup') == 'marketing'
        OR Session::get('userGroup') == 'clubleitung'))
        <a href="{{ URL::route('event.create') }}" 
           class="btn btn-sm btn-primary"
           data-toggle="tooltip" 
           data-placement="bottom" 
           title="Neue Veranstaltung erstellen">
                &nbsp;+&nbsp;
        </a>
    @else
        &nbsp;
    @endif
</div>
