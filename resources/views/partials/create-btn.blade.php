<div class="btn-group">
    @if(Session::has('userGroup')
        AND (Session::get('userGroup') == 'marketing'
        OR Session::get('userGroup') == 'clubleitung'))
        <a href="{{ URL::route('event.create') }}" 
           class="btn btn-sm btn-primary">
                Neue Veranstaltung
        </a>
    @else
        &nbsp;
    @endif
</div>
