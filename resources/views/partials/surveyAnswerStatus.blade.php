@if($answer->getPerson)
    @if( $answer->getPerson->prsn_status === 'candidate' )
        <i class="fa fa-adjust"
           style="color:yellowgreen;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="Kandidat"></i>
    @elseif ( $answer->getPerson->prsn_status === 'veteran' )
        <i class="fa fa-star"
           style="color:gold;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="Veteran"></i>
    @elseif ( $answer->getPerson->prsn_status === 'member')
        <i class="fa fa-circle"
           style="color:forestgreen;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="Aktiv"></i>
    @elseif ( $answer->getPerson->prsn_status === 'resigned' )
        <i class="fa fa-star-o"
           style="color:gold;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="ex-Mitglied"></i>
    @endif
@else
    <i class="fa fa-circle"
       style="color:lightgrey;"
       {{--data-toggle="tooltip"--}}
       {{--data-placement="top"--}}
       title="Extern"></i>
@endif