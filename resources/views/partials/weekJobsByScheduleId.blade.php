<!-- Needs variables: entries, persons, clubs -->

	@foreach($entries as $entry)
		
			<tr class="row">
				@if( is_null($entry->getPerson) )
				<td class="col-xs-3 col-md-3 red">
				@else
				<td class="col-xs-3 col-md-3 green">
				@endif
					<span class="word-break" 
						  data-toggle="tooltip" 
						  data-placement="top" 
						  title="{{ date("H:i", strtotime($entry->getJobType->jbtyp_time_start)) . 
						  "-" . 
						  date("H:i", strtotime($entry->getJobType->jbtyp_time_end)) }}">
						  	<small><strong>{{ $entry->getJobType->jbtyp_title }}</strong></small>
					</span>
				</td>

				<td class="col-xs-5 col-md-5">
				@if( is_null($entry->getPerson) )
					<div class="btn-group">
					
				   	{!! Form::text('userName' . $entry->id, 
				   	   			   Input::old('userName' . $entry->id), 
				   	   			   array('placeholder'=>'=FREI=', 
				   	   			   		 'id'=>'userName' . $entry->id, 
					   				     'class'=>'col-xs-8 col-md-8')) !!}

				   	{!! Form::hidden('ldapId' . $entry->id, '', array('id'=>'ldapId' . $entry->id) ) !!}

				   		<a class="btn-small btn-default dropdown-toggle hidden-print" data-toggle="dropdown" href="javascript:void(0);">
					        <span class="caret"></span>
					    </a>
					    <ul class="dropdown-menu">
				    		<li><a href="javascript:void(0);" 
					        	   onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{Session::get('userName')}}';
				        	   				document.getElementById('club{{ ''. $entry->id }}').value='{{Session::get('userClub')}}';
				        	   				document.getElementById('ldapId{{ ''. $entry->id }}').value='{{Session::get('userId')}}'">Ich mach's!</a>
					        </li>
					        <li role="presentation" class="divider"></li>
					    @foreach($persons as $person)
					        <li> 
					        	<a href="javascript:void(0);" 
					        	   onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{$person->prsn_name}}';
					        	   			document.getElementById('club{{ ''. $entry->id }}').value='{{$person->getClub->clb_title}}';
					        	   			document.getElementById('ldapId{{ ''. $entry->id }}').value='{{$person->prsn_ldap_id}}'">
					        	   			{{ $person->prsn_name }}
					        	   			@if ( $person->prsn_status === 'kandidat' ) 
					        	   				(K)
					        	   			@elseif ( $person->prsn_status === 'veteran' ) 
					        	   				(V)
					        	   			@endif
					        	   			{{ '(' . $person->getClub->clb_title . ')' }}
					        	</a>
					        </li>
						@endforeach
					    </ul> 	
					</div>
				@else
					<div class="btn-group">
					{!! Form::text('userName' . $entry->id, 
								   $entry->getPerson->prsn_name, 
								   array('id'=>'userName' . $entry->id, 
					   				     'class'=>'col-xs-8 col-md-8') ) !!}

					{!! Form::hidden('ldapId' . $entry->id, $entry->getPerson->prsn_ldap_id, array('id'=>'ldapId' . $entry->id) ) !!}

				   	<a class="btn-small btn-default dropdown-toggle hidden-print" data-toggle="dropdown" href="javascript:void(0);">
					        <span class="caret"></span>
					    </a>
					    <ul class="dropdown-menu">
					    	<li><a href="javascript:void(0);" 
					        	   onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{Session::get('userName')}}';
				        	   				document.getElementById('club{{ ''. $entry->id }}').value='{{Session::get('userClub')}}';
				        	   				document.getElementById('ldapId{{ ''. $entry->id }}').value='{{Session::get('userId')}}'">Ich mach's!</a>
					        </li>
					        <li role="presentation" class="divider"></li>
					    @foreach($persons as $person)
					        <li> 
					        	<a href="javascript:void(0);" 
					        	   onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{$person->prsn_name}}';
				        	   				document.getElementById('club{{ ''. $entry->id }}').value='{{$person->getClub->clb_title}}';
				        	   				document.getElementById('ldapId{{ ''. $entry->id }}').value='{{$person->prsn_ldap_id}}'">
				        	   				{{ $person->prsn_name }}
				        	   			@if ( $person->prsn_status === 'kandidat' ) 
				        	   				(K)
				        	   			@elseif ( $person->prsn_status === 'veteran' ) 
				        	   				(V)
				        	   			@endif
				        	   			{{ '(' . $person->getClub->clb_title . ')' }}
				        	   	</a>
				        	</li>
						@endforeach
					    </ul>  	
					</div>
				@endif
				</td>				   	
				
				<td class="col-xs-3 col-md-3">
					@if( is_null($entry->getPerson) )
						<div class="btn-group  hidden-print">
						   	{!! Form::text('club' . $entry->id, Input::old('club' . $entry->id),  
										   array( 'placeholder'=>'-', 
										  'id'=>'club' . $entry->id, 
					   				      'class'=>'col-xs-8 col-md-8') ) !!}
						 	<a class="btn-small btn-default dropdown-toggle hidden-print" data-toggle="dropdown" href="javascript:void(0);">
						        <span class="caret"></span>
						    </a>
						    <ul class="dropdown-menu">
						    @foreach($clubs as $club)
						        <li> 
						        	<a href="javascript:void(0);" 
						        	   onClick="document.getElementById('club{{ ''. $entry->id }}').value='{{$club}}'">{{$club}}</a>
						        </li>
							@endforeach
						    </ul>  	
					   	</div>
					@else
						<div class="btn-group">
							@if(!is_null($entry->getPerson->getClub))
								{!! Form::text('club' . $entry->id, 
											   $entry->getPerson->getClub->clb_title, 
											   array('id'=>'club' . $entry->id, 
					   				   		  'class'=>'col-xs-8 col-md-8')) !!}
							@else
								{!! Form::text('club' . $entry->id, 
											   array('id'=>'club' . $entry->id, 
					   				   		  'class'=>'col-xs-8 col-md-8')) !!}
							@endif
						   <a class="btn-small btn-default dropdown-toggle hidden-print" data-toggle="dropdown" href="javascript:void(0);">
						        <span class="caret"></span>
						    </a>
						    <ul class="dropdown-menu">
						    @foreach($clubs as $club)
						        <li> 
						        	<a href="javascript:void(0);" 
						        	onClick="document.getElementById('club{{ ''. $entry->id }}').value='{{$club}}'">
						        	{{$club}}</a>
						        </li>
							@endforeach
						    </ul>
						</div>
					@endif					
				</td>

				<td class="col-xs-1 col-md-1">
					@if( is_null($entry->getPerson) )
								<button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
									<i class="fa fa-comment-o"></i>
								</button>
					@else			
						@if( $entry->entry_user_comment == "" )
								<button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
									<i class="fa fa-comment-o"></i>
								</button>
						@else
								<button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
									<i class="fa fa-comment"></i>
								</button>
						@endif
					@endif
				</td>
			</tr>
			<tr class="hidden-print">
				<td colspan="4">
					@if( is_null($entry->getPerson) )
						<div class="hide">				
							{!! Form::text('comment' . $entry->id, 
						   				   Input::old('comment' . $entry->id),  
						   				   array('placeholder'=>'Kommentar hier hinzufügen', 
						   				   		 'class'=>'col-xs-12 col-md-12')) !!}
						</div>
					@else
						<div class="hide">				
							{!! Form::text('comment' . $entry->id, 
										   $entry->entry_user_comment, 
										   array('placeholder'=>'Kommentar hier hinzufügen',
										   		 'class'=>'col-xs-12 col-md-12')) !!}
						</div>
					@endif
				</td>
			</tr>

			<tr class="visible-xs hidden-md">
				<td colspan="4">&nbsp;</td>
			</tr>

	@endforeach