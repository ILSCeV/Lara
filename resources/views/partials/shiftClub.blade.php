@if( is_null($shift->getPerson) )   
    <div class="btn-group col-xs-10 col-md-10 hidden-print no-padding">
        {!! Form::text('club' . $shift->id, Input::old('club' . $shift->id),  
                       array( 'placeholder'=>'-', 
                       'id'=>'club' . $shift->id, 
                       'class'=>'col-xs-12 col-md-12',
                       'autocomplete'=>'off')) !!}
    </div>
@else   
    <div class="btn-group col-xs-10 col-md-10 no-padding">
        @if(!is_null($shift->getPerson->getClub))
            {!! Form::text('club' . $shift->id, 
                           $shift->getPerson->getClub->clb_title, 
                           array('id'=>'club' . $shift->id, 
                          'class'=>'col-xs-12 col-md-12',
                       	  'autocomplete'=>'off')) !!}
        @else
            {!! Form::text('club' . $shift->id, '',
                           array('id'=>'club' . $shift->id, 
                          'class'=>'col-xs-12 col-md-12',
                          'autocomplete'=>'off')) !!}
        @endif
    </div>
@endif 

<ul class="dropdown-menu dropdown-club" style="position: absolute;"></ul>
