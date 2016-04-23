<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">Dienstplan anpassen:</h4>
	</div>
	<div class="panel-body" id="main">

	{{-- jobtype fields --}}
	    <span hidden>{{$counter = 0}}</span>
	    <div id="container" class="container">
		    {{-- If there are entries passed - fill them with data and increment counter --}} 
		    @if(isset($entries))
		        @foreach($entries as $entry)
		            <div id={{ "box" . ++$counter }} class="box">
			           	<div class="input-append btn-group">
				           	<input type="text" 
				           		   name={{ "jobType" . $counter }}
				           		   class="input" 
				           		   id={{ "jobType" . $counter }}
				           		   value="{{ $entry->getJobType->jbtyp_title }}"
				           		   placeholder="Diensttyp hier eingeben"/>

							<span id="dropdown">
								<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
									<span class="caret"></span>
								</a>

								<ul class="dropdown-menu">
									@foreach($jobtypes as $jobtype)
										<li class="dropdown"> 
											<a href="javascript:void(0);" 
											   onClick="$(this).dropdownSelect(&#39;{{ $jobtype->jbtyp_title }}&#39;, 
											   								   &#39;{{ $jobtype->jbtyp_time_start }}&#39;, 
											   								   &#39;{{ $jobtype->jbtyp_time_end }}&#39;,
											   								   &#39;{{ $jobtype->jbtyp_statistical_weight }}&#39;);">

											   	{{  $jobtype->jbtyp_title }} 
											   	(<i class='fa fa-clock-o'></i>
												{{  date("H:i", strtotime($jobtype->jbtyp_time_start))
													. "-" .
												    date("H:i", strtotime($jobtype->jbtyp_time_end)) . ")" }}
											</a>
										</li>
									@endforeach
								</ul>
							</span>
						</div>
						&nbsp;&nbsp;&nbsp;&nbsp;

						<input type="time" class="input" 
							   name={{ "timeStart" . $counter }}
							   id={{ "timeStart" . $counter }}
							   value="{{ $entry->entry_time_start }}" 
							   required />

		            	<input type="time" 
		            		   class="input" 
		            		   name={{ "timeEnd" . $counter }} 
		            		   id={{ "timeEnd" . $counter }}
		            		   value="{{ $entry->entry_time_end }}" 
		            		   required />


		            	&nbsp;<br class="visible-xs">Gewicht:&nbsp;
		            	<input type="number" 
		            		   class="input" 
		            		   name={{ "jbtyp_statistical_weight" . $counter }} 
		            		   id={{ "jbtyp_statistical_weight" . $counter }}
		            		   value="{{ $entry->entry_statistical_weight }}" 
		            		   required />

		            	
		            	<input type="button" value="+" class="btn btn-small btn-success btnAdd" />
		            	&nbsp;&nbsp;
	    				<input type="button" value="&#8211;" class="btn btn-small btn-danger btnRemove" />
					</div>
					
		        @endforeach 
		    @endif

		    {{-- and add one empty entry --}}
		    <div id={{ "box" . ++$counter }} class="box">
	           	<div class="input-append btn-group">
		           	<input type="text" 
		           		   name={{ "jobType" . $counter }}
		           		   class="input" 
		           		   id={{ "jobType" . $counter }}
		           		   value=""
		           		   placeholder="Diensttyp hier eingeben"/>

					<span id="dropdown">
						<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
							<span class="caret"></span>
						</a>

						<ul class="dropdown-menu">
							@foreach($jobtypes as $jobtype)
								<li class="dropdown"> 
									<a href="javascript:void(0);" 
									   onClick="$(this).dropdownSelect(&#39;{{ $jobtype->jbtyp_title }}&#39;, 
									   								   &#39;{{ $jobtype->jbtyp_time_start }}&#39;, 
									   								   &#39;{{ $jobtype->jbtyp_time_end }}&#39;,
									   								   &#39;{{ $jobtype->jbtyp_statistical_weight }}&#39;);">

									   	{{  $jobtype->jbtyp_title }} 
									   	(<i class='fa fa-clock-o'></i>
										{{  date("H:i", strtotime($jobtype->jbtyp_time_start))
											. "-" .
										    date("H:i", strtotime($jobtype->jbtyp_time_end)) . ")" }}
									</a>
								</li>
							@endforeach
						</ul>
					</span>
				</div>

				&nbsp;&nbsp;&nbsp;&nbsp;
				<br class="visible-xs">

				<input type="time" class="input" 
					   name={{ "timeStart" . $counter }}
					   id={{ "timeStart" . $counter }}
					   value="21:00" required />
	        	

	        	<input type="time" 
	        		   class="input" 
	        		   name={{ "timeEnd" . $counter }} 
	        		   id={{ "timeEnd" . $counter }}
	        		   value="01:00" required />

				&nbsp;<br class="visible-xs">Gewicht:&nbsp;
	        	<input type="number" 
	        		   class="input" 
	        		   name={{ "jbtyp_statistical_weight" . $counter }} 
	        		   id={{ "jbtyp_statistical_weight" . $counter }}
	        		   value="1"
	        		   placeholder="Statistische Wertung" required />

	        	<input type="button" value="+" class="btn btn-small btn-success btnAdd" /> 
	        	&nbsp;&nbsp;
				<input type="button" value="&#8211;" class="btn btn-small btn-danger btnRemove" />
				<br class="visible-xs"><br class="visible-xs">
	    	</div>
	    	<br>
			<input type="hidden" name="counter" id="counter" value="{{$counter}}" />
		</div>
	</div>
</div>