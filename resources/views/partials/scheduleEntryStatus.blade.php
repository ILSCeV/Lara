{{ Form::button('<i class="fa fa-check"
                   data-toggle="tooltip"
                   data-placement:"top"
                   title="Änderungen speichern"></i>', 
                array('type' => 'submit', 
                      'name' => 'btn-submit-change' . $entry->id,
                      'id' => 'btn-submit-changes' . $entry->id, 
                      'class' => 'btn btn-small btn-success hide')) }}
 
@if( is_null($entry->getPerson) )

    <i class="fa fa-circle-o"
       name="status-icon" 
       style="color:lightgrey;"
       data-toggle="tooltip" 
       data-placement="top" 
       title="Dienst frei"></i>

@else

    @if     ( $entry->getPerson->prsn_status === 'candidate' ) 
        <i class="fa fa-adjust" 
           name="status-icon"
           style="color:yellowgreen;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="Kandidat"></i>
    @elseif ( $entry->getPerson->prsn_status === 'veteran' ) 
        <i class="fa fa-star" 
           name="status-icon"
           style="color:gold;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="Veteran"></i>
    @elseif ( $entry->getPerson->prsn_status === 'member')
        <i class="fa fa-circle" 
           name="status-icon"
           style="color:forestgreen;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="Aktiv"></i>
    @elseif ( $entry->getPerson->prsn_status === 'resigned' ) 
        <i class="fa fa-star-o" 
           name="status-icon"
           style="color:gold;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="ex-Mitglied"></i>
    @elseif ( empty($entry->getPerson->prsn_status) )
        <i class="fa fa-circle" 
           name="status-icon"
           style="color:lightgrey;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="Extern"></i>
    @endif

@endif