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
       title="{{ trans('mainLang.jobFree') }}"></i>

@else

    @if     ( $entry->getPerson->prsn_status === 'candidate' ) 
        <i class="fa fa-adjust" 
           name="status-icon"
           style="color:yellowgreen;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="{{ trans('mainLang.candidate') }}"></i>
    @elseif ( $entry->getPerson->prsn_status === 'veteran' ) 
        <i class="fa fa-star" 
           name="status-icon"
           style="color:gold;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="{{ trans('mainLang.veteran') }}"></i>
    @elseif ( $entry->getPerson->prsn_status === 'member')
        <i class="fa fa-circle" 
           name="status-icon"
           style="color:forestgreen;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="{{ trans('mainLang.active') }}"></i>
    @elseif ( $entry->getPerson->prsn_status === 'resigned' ) 
        <i class="fa fa-star-o" 
           name="status-icon"
           style="color:gold;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="{{ trans('mainLang.ex-member') }}"></i>
    @elseif ( empty($entry->getPerson->prsn_status) )
        <i class="fa fa-circle" 
           name="status-icon"
           style="color:lightgrey;"
           data-toggle="tooltip" 
           data-placement="top" 
           title="{{ trans('mainLang.external') }}"></i>
    @endif

@endif