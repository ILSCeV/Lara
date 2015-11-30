<!-- Needs variables: entry, persons -->

@if( is_null($entry->getPerson) )

    <i class="fa fa-circle-o" 
       style="color:lightgrey;"
       data-toggle="tooltip" 
       data-placement="top" 
       title="Dienst frei"></i>

@else

    @if     ( $entry->getPerson->prsn_status === 'candidate' ) 
        <i class="fa fa-adjust" 
           style="color:yellowgreen;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="Kandidat"></i>
    @elseif ( $entry->getPerson->prsn_status === 'veteran' ) 
        <i class="fa fa-star" 
           style="color:gold;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="Veteran"></i>
    @elseif ( $entry->getPerson->prsn_status === 'member')
        <i class="fa fa-circle" 
           style="color:forestgreen;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="Aktiv"></i>
    @elseif ( $entry->getPerson->prsn_status === 'retired' ) 
        <i class="fa fa-star-o" 
           style="color:gold;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="ex-Mitglied"></i>
    @elseif ( empty($entry->getPerson->prsn_status) )
        <i class="fa fa-circle" 
           style="color:lightgrey;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="Extern"></i>
    @endif

@endif