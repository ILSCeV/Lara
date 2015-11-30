<!-- Needs variables: entries, persons, clubs -->

@foreach($entry as $entry)
    
    <div class="row">
        {!! Form::open(  array( 'id' => $entry->id, 
                                        'route' => 'entry.update', 
                                        'method' => 'put', 
                                        'class' => 'scheduleEntry')  ) !!}

        <div class="col-xs-3 col-md-3">
            @include("partials.scheduleEntryTitle", $entry)
        </div>

        <div class="col-xs-5 col-md-5 input-append btn-group">      
            @include("partials.scheduleEntryName", [$entry, $persons])
        </div>                
            
        <div class="col-xs-3 col-md-3">
            @include("partials.scheduleEntryClub", [$entry, $clubs])                 
        </div>

        <!-- SMALL COMMENT ICON --> 
        <!-- col-md/xs-1 for the icon -->
        <!-- col-md/xs-12 for the comment input -->
            @include("partials.scheduleEntryCommentHidden", $entry)

        {!! Form::submit( 'save', array('id' => 'btn-add-setting', 'hidden') ) !!}
        {!! Form::close() !!}
    </div>
@endforeach
