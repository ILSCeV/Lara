@if( $clubEvent->getSchedule->schdl_password != '')
<div class="hidden-print panel panel-heading">
    {!! Form::password('password', array('required', 
                                         'class'=>'col-md-4 col-xs-12',
                                         'placeholder'=>'Passwort hier eingeben')) !!}
    <br />
</div> 

@endif 
<div class="panel-body">
@foreach($entries as $entry)
    <div class="row">
        {!! Form::open(  array( 'route' => ['entry.update', $entry->id],
                                'id' => $entry->id, 
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

        {{-- LARGE COMMENT SECTION --}} 
        <div class="col-md-5 col-xs-5">
            @include("partials.scheduleEntryCommentFull", $entry)
        </div>

        {!! Form::submit( 'save', array('id' => 'btn-add-setting', 'hidden') ) !!}
        {!! Form::close() !!}
    </div>
@endforeach
</div>