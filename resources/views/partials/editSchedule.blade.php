<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ trans('mainLang.adjustRoster') }}:</h4>
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
				           		   name={{ "jbtyp_title" . $counter }}
				           		   class="input" 
				           		   id={{ "jbtyp_title" . $counter }}
				           		   value="{{ $entry->getJobType->jbtyp_title }}"
				           		   placeholder="{{ trans('mainLang.serviceTypeEnter') }}"/>
				           	
				           	<ul class="dropdown-menu dropdown-jobtypes" style="position: absolute;">
				    		</ul>

						</div>
						&nbsp;&nbsp;&nbsp;&nbsp;

						<input type="time" class="input" 
							   name={{ "jbtyp_time_start" . $counter }}
							   id={{ "jbtyp_time_start" . $counter }}
							   value="{{ $entry->entry_time_start }}" 
							   required />

		            	<input type="time" 
		            		   class="input" 
		            		   name={{ "jbtyp_time_end" . $counter }} 
		            		   id={{ "jbtyp_time_end" . $counter }}
		            		   value="{{ $entry->entry_time_end }}" 
		            		   required />


		            	&nbsp;<br class="visible-xs">{{ trans('mainLang.weight') }}:&nbsp;
		            	<input type="number" 
		            		   class="input" 
		            		   name={{ "jbtyp_statistical_weight" . $counter }} 
		            		   id={{ "jbtyp_statistical_weight" . $counter }}
		            		   value="{{ $entry->entry_statistical_weight }}"
							   onkeypress="return event.charCode >= 48"
							   min="0"
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
		           		   name={{ "jbtyp_title" . $counter }}
		           		   class="input" 
		           		   id={{ "jbtyp_title" . $counter }}
		           		   value=""
		           		   placeholder="{{ trans('mainLang.serviceTypeEnter') }}"/>

					<ul class="dropdown-menu dropdown-jobtypes" style="position: absolute;">
				    </ul>
				</div>

				&nbsp;&nbsp;&nbsp;&nbsp;
				<br class="visible-xs">

				<input type="time" class="input" 
					   name={{ "jbtyp_time_start" . $counter }}
					   id={{ "jbtyp_time_start" . $counter }}
					   value="21:00" required />
	        	

	        	<input type="time" 
	        		   class="input" 
	        		   name={{ "jbtyp_time_end" . $counter }} 
	        		   id={{ "jbtyp_time_end" . $counter }}
	        		   value="01:00" required />

				&nbsp;<br class="visible-xs">{{ trans('mainLang.weight') }}:&nbsp;
	        	<input type="number" 
	        		   class="input" 
	        		   name={{ "jbtyp_statistical_weight" . $counter }} 
	        		   id={{ "jbtyp_statistical_weight" . $counter }}
	        		   value="1"
					   onkeypress="return event.charCode >= 48"
					   min="0"
	        		   placeholder="{{ trans('mainLang.statisticalEvaluation') }}" required />

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