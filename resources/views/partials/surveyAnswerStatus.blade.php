@if($answer->getPerson)
    @if( $answer->getPerson->prsn_status === 'candidate' )
        <i class="fa fa-adjust"
           name="status-icon"
           style="color:yellowgreen;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="{{ trans('mainLang.candidate') }}"></i>
    @elseif ( $answer->getPerson->prsn_status === 'veteran' )
        <i class="fa fa-star"
           name="status-icon"
           style="color:gold;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="{{ trans('mainLang.veteran') }}"></i>
    @elseif ( $answer->getPerson->prsn_status === 'member')
        <i class="fa fa-circle"
           name="status-icon"
           style="color:forestgreen;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="{{ trans('mainLang.active') }}"></i>
    @elseif ( $answer->getPerson->prsn_status === 'resigned' )
        <i class="fa fa-star-o"
           name="status-icon"
           style="color:gold;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="{{ trans('mainLang.ex-member') }}"></i>
    @else
        <i class="fa fa-circle"
           style="color:lightgrey;"
           {{--data-toggle="tooltip"--}}
           {{--data-placement="top"--}}
           title="{{ trans('mainLang.external') }}"></i>
    @endif
@else
    <i class="fa fa-circle"
       name="status-icon"
       style="color:lightgrey;"
       {{--data-toggle="tooltip"--}}
       {{--data-placement="top"--}}
       title="{{ trans('mainLang.external') }}"></i>
@endif