@if( is_null($entry->getPerson) )
    <div class="btn-group col-xs-10 col-md-10 hidden-print no-padding">

        {!! Form::text('club' . $entry->id, Input::old('club' . $entry->id),  
                       array( 'placeholder'=>'-', 
                      'id'=>'club' . $entry->id, 
                      'class'=>'col-xs-10 col-md-10') ) !!}
		
		<div class="col-xs-2 col-md-2 no-padding">
			
			<a class="btn-small btn-default dropdown-toggle hidden-print" 
			   data-toggle="dropdown" 
			   href="javascript:void(0);">
			    <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
			    @foreach($clubs as $club)
			        <li> 
			            <a href="javascript:void(0);" 
			               onClick="document.getElementById('club{{ ''. $entry->id }}').value='{{$club}}';
			                        document.getElementById('btn-submit-changes{{ ''. $entry->id }}').click();">
			                {{$club}}
			            </a>
			        </li>
			    @endforeach
			</ul>  
			
		</div>

    </div>

@else
    
    <div class="btn-group col-xs-10 col-md-10 no-padding">

        @if(!is_null($entry->getPerson->getClub))
            {!! Form::text('club' . $entry->id, 
                           $entry->getPerson->getClub->clb_title, 
                           array('id'=>'club' . $entry->id, 
                          'class'=>'col-xs-10 col-md-10')) !!}
        @else
            {!! Form::text('club' . $entry->id, 
                           array('id'=>'club' . $entry->id, 
                          'class'=>'col-xs-10 col-md-10')) !!}
        @endif

        <div class="col-xs-2 col-md-2 no-padding">

			<a class="btn-small btn-default dropdown-toggle hidden-print" 
			   data-toggle="dropdown" 
			   href="javascript:void(0);">
			    <span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
			    @foreach($clubs as $club)
			        <li> 
			            <a href="javascript:void(0);" 
			               onClick="document.getElementById('club{{ ''. $entry->id }}').value='{{$club}}';
			                        document.getElementById('btn-submit-changes{{ ''. $entry->id }}').click();">
			                {{$club}}
			            </a>
			        </li>
			    @endforeach
			</ul>   
			
		</div>

    </div>
@endif 
