<!-- Needs variables: entries, persons, clubs -->

@if( $clubEvent->getSchedule->schdl_password != '')
<div class="well no-padding hidden-print">
    {!! Form::password('password', array('required', 
                                         'class'=>'col-md-12 col-xs-12',
                                         'placeholder'=>'Passwort hier eingeben')) !!}
    <br />
</div> 
@endif 


@foreach($entries as $entry)
    <div class="row">

        {!! Form::open(  array( 'route' => ['entry.update', $entry->id],
                                'id' => $entry->id, 
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

            
        {!! Form::submit( 'save', array('id' => 'btn-submit-changes' . $entry->id, 'hidden') ) !!}
        {!! Form::close() !!}
    </div>
@endforeach
