@if( is_null($entry->getPerson) )   
    <div class="btn-group col-xs-10 col-md-10 hidden-print no-padding">
        {!! Form::text('club' . $entry->id, Input::old('club' . $entry->id),  
                       array( 'placeholder'=>'-', 
                       'id'=>'club' . $entry->id, 
                       'class'=>'col-xs-12 col-md-12',
                       'autocomplete'=>'off')) !!}
    </div>
@else   
    <div class="btn-group col-xs-10 col-md-10 no-padding">
        @if(!is_null($entry->getPerson->getClub))
            {!! Form::text('club' . $entry->id, 
                           $entry->getPerson->getClub->clb_title, 
                           array('id'=>'club' . $entry->id, 
                          'class'=>'col-xs-12 col-md-12',
                       	  'autocomplete'=>'off')) !!}
        @else
            {!! Form::text('club' . $entry->id, '',
                           array('id'=>'club' . $entry->id, 
                          'class'=>'col-xs-12 col-md-12',
                          'autocomplete'=>'off')) !!}
        @endif
    </div>
@endif 

<ul class="dropdown-menu dropdown-club" style="position: absolute;"></ul>
