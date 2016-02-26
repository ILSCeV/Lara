@if( is_null($entry->getPerson) )

    {!! Form::hidden('timestamp' . $entry->id, 
                     '', 
                     array('id'=>'timestamp' . $entry->id) ) 
    !!}

@else

    {!! Form::hidden('timestamp' . $entry->id, 
                     $entry->updated_at, 
                     array('id'=>'timestamp' . $entry->id) ) 
    !!}

@endif