@if( is_null($entry->getPerson) )   
    {!! Form::text('comment' . $entry->id, 
                   Input::old('comment' . $entry->id),  
                   array('placeholder'=>'Kommentar hier hinzufügen',
                         'id'=>'comment' . $entry->id, 
                         'class'=>'col-xs-12 col-md-12')) 
    !!}
 @else
    {!! Form::text('comment' . $entry->id, 
                   $entry->entry_user_comment, 
                   array('placeholder'=>'Kommentar hier hinzufügen',
                         'id'=>'comment' . $entry->id,
                         'class'=>'col-xs-12 col-md-12')) 
    !!}
@endif