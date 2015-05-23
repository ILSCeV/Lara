<!-- Needs variables: entries, persons, clubs -->

	@foreach($entries as $entry)
		
			<tr>
				<td class="hidden-xs">
					&nbsp;
				</td>
				@if( is_null($entry->getPerson) )
					<td class="col-xs-2 col-md-2 red">
				@else
					<td class="col-xs-2 col-md-2 green">
				@endif
					<span class="word-break" 
						  data-toggle="tooltip" 
						  data-placement="top" 
						  title="{{ date("H:i", strtotime($entry->entry_time_start)) . 
						  			"-" . 
						  			date("H:i", strtotime($entry->entry_time_end)) }}" >
						  <small>
						  		{{ $entry->getJobType->jbtyp_title }}
						  		<span class="entry-time hide">
						  			<br class="visible-xs">
									{!! "(" . date("H:i", strtotime($entry->entry_time_start))
									. "-" . "<br class='visible-xs'>" .
								    date("H:i", strtotime($entry->entry_time_end)) . ")" !!}
						  		</span>

						  </small>
					</span>
				</td>

				<td class="col-xs-4 col-md-2">
				
<!-- if entry is free - let anyone edit it -->
					@if( is_null($entry->getPerson) )			

					   	<div class="input-append btn-group">

<!-- STATUS -->
							<div class="col-xs-2 col-md-2 no-padding" id="clubStatus{{ $entry->id }}">
								<i class="fa fa-circle-o" 
								   style="color:lightgrey;"
								   data-toggle="tooltip" 
					  			   data-placement="top" 
					  			   title="Dienst frei"></i>&nbsp;
	        	   			</div>

<!-- NAME -->
						   	{!! Form::text( 'userName' . $entry->id, 
						   					Input::old('userName' . $entry->id), 
						   					array('placeholder'=>'=FREI=', 
						   						  'id'=>'userName' . $entry->id, 
						   				     	  'class'=>'col-xs-8 col-md-8')) !!}

<!-- LDAP ID -->
						   	{!! Form::hidden( 'ldapId' . $entry->id, 
						   					  '', 
						   					  array('id'=>'ldapId' . $entry->id) ) !!}

<!-- DROPDOWN USERNAMES -->
						   	<div class="col-xs-2 col-md-2 no-padding">
							   	@if( Session::get('userId') ) 
							   		<a class="btn-small btn-default dropdown-toggle" 
							   		   data-toggle="dropdown" 
							   		   href="javascript:void(0);">
								       	<span class="caret"></span>
								    </a>

								    <ul class="dropdown-menu">
							    		<li>
							    			<a href="javascript:void(0);" 
								        	   onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{Session::get('userName')}}';
							        	   				document.getElementById('club{{ ''. $entry->id }}').value='{{Session::get('userClub')}}';
							        	   				document.getElementById('ldapId{{ ''. $entry->id }}').value='{{Session::get('userId')}}'">
							        	   		Ich mach's!
							        	   	</a>
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
								@else
									&nbsp;
								@endif 	
							</div>
						</div>

					@else 
					
<!-- if entry is used by a guest (no LDAP id set) - let anyone edit it -->
						@if( !isset($entry->getPerson->prsn_ldap_id) ) 

							<div class="input-append btn-group">

<!-- STATUS -->
								<div class="col-xs-2 col-md-2 no-padding" id="clubStatus{{ $entry->id }}">
									<i class="fa fa-circle" 
									   style="color:lightgrey;"
									   data-toggle="tooltip" 
						  			   data-placement="top" 
						  			   title="Extern"></i>
		        	   			</div>

<!-- NAME -->
								{!! Form::text('userName' . $entry->id, 
												$entry->getPerson->prsn_name, 
												array('id'=>'userName' . $entry->id, 
						   				     		  'class'=>'col-xs-8 col-md-8') ) !!}

<!-- LDAP ID -->
								{!! Form::hidden('ldapId' . $entry->id, 
												 '', 
												 array('id'=>'ldapId' . $entry->id) ) !!}

<!-- DROPDOWN USERNAMES -->
							   	<div class="col-xs-2 col-md-2 no-padding">
								   	@if( Session::get('userId') ) 
								   	<a class="btn-small btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
									        <span class="caret"></span>
									    </a>
									    <ul class="dropdown-menu">
									    	<li>
									    		<a href="javascript:void(0);" 
									        	   onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{Session::get('userName')}}';
								        	   				document.getElementById('club{{ ''. $entry->id }}').value='{{Session::get('userClub')}}';
								        	   				document.getElementById('ldapId{{ ''. $entry->id }}').value='{{Session::get('userId')}}'">
								        	   		Ich mach's!
								        	   	</a>
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
									@endif
								</div>
							</div>

						@else
						
<!-- if entry is used by a member (LDAP id set) - let only other members edit it -->
							@if( Session::get('userId') ) 
								<div class="input-append btn-group">

<!-- STATUS -->
								<div class="col-xs-2 col-md-2 no-padding" id="clubStatus{{ $entry->id }}">
									@if 	( $entry->getPerson->prsn_status === 'kandidat' ) 
			        	   				<i class="fa fa-adjust" 
			        	   				   style="color:yellowgreen;"
										   data-toggle="tooltip" 
							  			   data-placement="top" 
							  			   title="Kandidat"></i>
			        	   			@elseif ( $entry->getPerson->prsn_status === 'veteran' ) 
			        	   				<i class="fa fa-star" 
			        	   				   style="color:gold;"
										   data-toggle="tooltip" 
							  			   data-placement="top" 
							  			   title="Veteran"></i>
			        	   			@elseif ( $entry->getPerson->prsn_status === 'member')
			        	   				<i class="fa fa-circle" 
			        	   				   style="color:forestgreen;"
										   data-toggle="tooltip" 
							  			   data-placement="top" 
							  			   title="Aktiv"></i>
			        	   			@endif
		        	   			</div>						

<!-- NAME -->									
								{!! Form::text('userName' . $entry->id, 
												$entry->getPerson->prsn_name, 
												array('id'=>'userName' . $entry->id, 
					   				     			  'class'=>'col-xs-8 col-md-8') ) !!}

<!-- LDAP ID -->
								{!! Form::hidden('ldapId' . $entry->id, $entry->getPerson->prsn_ldap_id, array('id'=>'ldapId' . $entry->id) ) !!}
						
<!-- DROPDOWN USERNAMES -->		
								<div class="col-xs-2 col-md-2 no-padding">
									@if( Session::get('userId') ) 
										<a class="btn-small btn-default dropdown-toggle" 
										   data-toggle="dropdown" 
										   href="javascript:void(0);">
									        <span class="caret"></span>
									    </a>

									    <ul class="dropdown-menu">
									    	<li>
									    		<a href="javascript:void(0);" 
									        	   onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{Session::get('userName')}}';
								        	   				document.getElementById('club{{ ''. $entry->id }}').value='{{Session::get('userClub')}}';
								        	   				document.getElementById('ldapId{{ ''. $entry->id }}').value='{{Session::get('userId')}}'">
								        	   		Ich mach's!
								        	   	</a>
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
									@endif
								</div>
							
							@else			
							
<!-- Guest are not allowed to edit members - read-only representation -->
							
<!-- STATUS -->
								<div class="col-xs-2 col-md-2 no-padding" id="clubStatus{{ $entry->id }}">
									@if 	( $entry->getPerson->prsn_status === 'kandidat' ) 
			        	   				<i class="fa fa-adjust" 
			        	   				   style="color:yellowgreen;"
										   data-toggle="tooltip" 
							  			   data-placement="top" 
							  			   title="Kandidat"></i>
			        	   			@elseif ( $entry->getPerson->prsn_status === 'veteran' ) 
			        	   				<i class="fa fa-star" 
			        	   				   style="color:gold;"
										   data-toggle="tooltip" 
							  			   data-placement="top" 
							  			   title="Veteran"></i>
			        	   			@elseif ( $entry->getPerson->prsn_status === 'member')
			        	   				<i class="fa fa-circle" 
			        	   				   style="color:forestgreen;"
										   data-toggle="tooltip" 
							  			   data-placement="top" 
							  			   title="Aktiv"></i>
			        	   			@endif
		        	   			</div>				

<!-- NAME -->
								{!! Form::text( 'userName' . $entry->id, 
												$entry->getPerson->prsn_name, 
												array('readonly', 
					   				     	  		  'class'=>'col-xs-8 col-md-8')) !!}

<!-- LDAP ID -->
								{!! Form::hidden('ldapId' . $entry->id, $entry->getPerson->prsn_ldap_id ) !!}

							@endif
						@endif
					@endif
				</td>


				<td class="col-xs-4 col-md-2">

<!-- if entry is free - let anyone edit it -->
					@if( is_null($entry->getPerson) )
						<div class="input-append btn-group">

<!-- CLUB -->
						   	{!! Form::text('club' . $entry->id, Input::old('club' . $entry->id),  
										   array( 'placeholder'=>'-', 
										  		  'id'=>'club' . $entry->id, 
					   				     		  'class'=>'col-xs-8 col-md-8') ) !!}

<!-- CLUB DROPDOWN -->
						 	<a class="btn-small btn-default dropdown-toggle" 
						 	   data-toggle="dropdown" 
						 	   href="javascript:void(0);">
						        <span class="caret"></span>
						    </a>

						    <ul class="dropdown-menu">
							    @foreach($clubs as $club)
							        <li> 
							        	<a href="javascript:void(0);" 
							        	   onClick="document.getElementById('club{{ ''. $entry->id }}').value='{{$club}}'">
							        	   	{{$club}}
							        	</a>
							        </li>
								@endforeach
						    </ul>  	
					   	</div>
					@else

<!-- if entry is used by a guest (no LDAP id set) - let anyone edit it -->
						@if( !isset($entry->getPerson->prsn_ldap_id) )

							<div class="input-append btn-group">

<!-- CLUB -->
								@if(!is_null($entry->getPerson->getClub))
									{!! Form::text('club' . $entry->id, 
												   $entry->getPerson->getClub->clb_title, 
												   array('id'=>'club' . $entry->id, 
					   				     	  			 'class'=>'col-xs-8 col-md-8')) !!}
								@else
									{!! Form::text('club' . $entry->id, 
												   array('id'=>'club' . $entry->id)) !!}
								@endif

<!-- CLUB DROPDOWN -->
							   	<a class="btn-small btn-default dropdown-toggle" 
							   	   data-toggle="dropdown" 
							   	   href="javascript:void(0);">
							        <span class="caret"></span>
							    </a>

							    <ul class="dropdown-menu">
								    @foreach($clubs as $club)
								        <li> 
								        	<a href="javascript:void(0);" 
								        	   onClick="document.getElementById('club{{ ''. $entry->id }}').value='{{$club}}'">
								        		{{$club}}
								        	</a>
								        </li>
									@endforeach
							    </ul>

							</div>
						@else

<!-- if entry is used by a member (LDAP id set) - let only other members edit it -->
							@if( Session::get('userId') ) 
								<div class="input-append btn-group">
<!-- CLUB -->
									@if(!is_null($entry->getPerson->getClub))
										{!! Form::text('club' . $entry->id, 
													   $entry->getPerson->getClub->clb_title, 
													   array('id'=>'club' . $entry->id, 
					   				     	  				 'class'=>'col-xs-8 col-md-8') ) !!}
									@else
										{!! Form::text('club' . $entry->id, 
													   array('id'=>'club' . $entry->id) ) !!}
									@endif		

<!-- CLUB DROPDOWN -->						
								    <a class="btn-small btn-default dropdown-toggle" 
								       data-toggle="dropdown" 
								       href="javascript:void(0);">
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
							
							@else

							<!-- Guest are not allowed to edit members - read-only representation -->

								{!! Form::text('club' . $entry->id, 
								    		    (!is_null($entry->getPerson->getClub)) ? 
								    		    $entry->getPerson->getClub->clb_title : 
								    		    array('placeholder'=>'-'), array('readonly', 
					   				     	  		  'class'=>'col-xs-8 col-md-8')) !!}

							@endif
						@endif
					@endif					
				</td>
				
				<td class="col-xs-3 col-md-6">

<!-- if entry is free - let anyone edit it -->
					@if( is_null($entry->getPerson) )

<!-- COMMENT -->
					   	{!! Form::text('comment' . $entry->id, Input::old('comment' . $entry->id),  
					   				   array('placeholder'=>'-', 
					   				   'class'=>'col-xs-12 col-md-12')) !!}
					
					@else
					
<!-- if entry is used by a guest (no LDAP id set) - let anyone edit it -->
						@if( !isset($entry->getPerson->prsn_ldap_id) )

<!-- COMMENT -->
							{!! Form::text('comment' . $entry->id, 
								   		   $entry->entry_user_comment, 
								   		   array('placeholder'=>'-',
								   		   		 'class'=>'col-xs-12 col-md-12')) !!}
						@else
						
<!-- if entry is used by a member (LDAP id set) - let only other members edit it -->
							@if( Session::get('userId') ) 

<!-- COMMENT -->
								{!! Form::text('comment' . $entry->id, 
								   			   $entry->entry_user_comment,
								   			   array('placeholder'=>'-',
								   			   		 'class'=>'col-xs-12 col-md-12')) !!}
							@else

<!-- COMMENT -->
								{!! Form::text('comment' . $entry->id, 
								   	$entry->entry_user_comment, 
								   	array('placeholder'=>'-',
								   		  'class'=>'col-xs-12 col-md-12', 'readonly')) !!}
							@endif
						@endif
					@endif
				</td>

				<td class="hidden-xs">
					&nbsp;
				</td> 

			</tr>

	@endforeach