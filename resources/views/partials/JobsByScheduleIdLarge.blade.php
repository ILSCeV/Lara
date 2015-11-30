<!-- Needs variables: entries, persons, clubs -->

@foreach($entry as $entry)
    
    <div class="row">
        {!! Form::open(  array( 'id' => $entry->id, 
                                        'route' => 'entry.update', 
                                        'method' => 'put', 
                                        'class' => 'scheduleEntry')  ) !!}

        <div class="col-xs-2 col-md-2">
            @include("partials.scheduleEntryTitle", $entry)
        </div>

        <div class="col-xs-3 col-md-3 input-append btn-group">      
            @include("partials.scheduleEntryName", [$entry, $persons])
        </div>               
            
        <div class="col-xs-2 col-md-2">
            @include("partials.scheduleEntryClub", [$entry, $clubs])                 
        </div>

        <!-- LARGE COMMENT SECTION --> 
        <div class="col-md-5 col-xs-5">
            @include("partials.scheduleEntryCommentFull", $entry)
        </div>

        {!! Form::submit( 'save', array('id' => 'btn-add-setting', 'hidden') ) !!}
        {!! Form::close() !!}
    </div>
@endforeach