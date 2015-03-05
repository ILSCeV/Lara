{{-- Needs variables: schedule, templates, jobtypes, entries --}}

		<!-- Shows dynamic form fields for new job types -->
		<script>
		    $(document).ready(function() {
		        // initialise counter
		        var iCnt = 0;

		        var dropdown = '<span id="dropdown" style="position:inherit;">' + 
		  					   '<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);"><span class="caret"></span></a>'+
							   '<ul class="dropdown-menu">' +
							   '@foreach($jobtypes as $jobtype)' + 
							   '<li class="dropdown"> <a href="javascript:void(0);" onClick="$(this).dropdownSelect(' +
							   	'&#39;{{$jobtype}}&#39;' + 
							   	');">{{$jobtype}}</a></li>' +
							   '@endforeach' + 
		    				   '</ul></span>';
		  
		    	// If there are entries passed - fill them with data and increment counter
			    @if(isset($entries))
			    	@foreach($entries as $entry)
				  	$( document ).ready(function() {    
			                iCnt = iCnt + 1;
			                // copy counter to input form
			                $('#counter').val(iCnt);	
	 
			                // ADD FIELDS
			                $('<div id="box'+iCnt+'"></div>').appendTo('#container');
			                $('#box'+iCnt).append('<input type=text name="jobType' + iCnt + '" class="input" id="jobType' + iCnt + '" ' +
			                            		'value="' + '{{ $entry->getJobType->jbtyp_title }}' + '" placeholder="Diensttyp hier eingeben"/>');
			                // copy dropdown to current box
			                $(dropdown).clone().appendTo('#box'+iCnt);
			                $('#dropdown').attr("id","dropdown" + iCnt);
			                
			                // time added later, needed for statistics
			                //$('#box'+iCnt).append('&nbsp;&nbsp;&nbsp;&nbsp;<input type="time" class="input" name="timeStart' + iCnt + '" id="timeStart' + iCnt + '" name="timeStart' + iCnt + '" value="21:00"/>');
			                //$('#box'+iCnt).append('&nbsp;&nbsp;&nbsp;&nbsp;<input type="time" class="input" name="timeStart' + iCnt + '" id="timeEnd' + iCnt + '" name="timeStart' + iCnt + '" value="01:00"/>');
			                
			        });
					@endforeach 
				@endif

		        // Add one more job with every click on "+"
		        $('#btAdd').click(function() {    
		                iCnt = iCnt + 1;
		                // copy counter to input form
		                $('#counter').val(iCnt);	

		                // ADD FIELDS
		                $('<div id="box'+iCnt+'"></div>').appendTo('#container');
		                $('#box'+iCnt).append('<input type=text name="jobType' + iCnt + '" class="input" id="jobType' + iCnt + '" ' +
		                            		'value="" placeholder="Diensttyp hier eingeben"/>');
		                // copy dropdown to current box
		                $(dropdown).clone().appendTo('#box'+iCnt);
		                $('#dropdown').attr("id","dropdown" + iCnt);
		                
		                // time added later, needed for statistics
		                //$('#box'+iCnt).append('&nbsp;&nbsp;&nbsp;&nbsp;<input type="time" class="input" name="timeStart' + iCnt + '" id="timeStart' + iCnt + '" name="timeStart' + iCnt + '" value="21:00"/>');
		                //$('#box'+iCnt).append('&nbsp;&nbsp;&nbsp;&nbsp;<input type="time" class="input" name="timeStart' + iCnt + '" id="timeEnd' + iCnt + '" name="timeStart' + iCnt + '" value="01:00"/>');
		                
		        });

		        // Remove the last job with every click on "-"
		        $('#btRemove').click(function() {   
		            if (iCnt != 0) { 
		            	$('#box' + iCnt).empty(); 
		            	$('#box' + iCnt).remove(); 
		            	iCnt = iCnt - 1; 
		            	$('#counter').val(iCnt);
		            }
		        });

		        $.fn.dropdownSelect = function(jobtype, timeStart, timeEnd) {
		        	$(this).closest('div').find('input').val(jobtype);
		        	// time added later, needed for statistics
		        	//alert($(this).closest('div').find('input').next().next().val(timeStart));
		        	//alert($(this).closest('div').find('input').next().next().val(timeEnd));
		    	};( jQuery );
		    });
		</script>

<br>
<div class="panel">
	<div class="panel-title">
		<h4>Dienstplan anpassen:</h4>
	</div>
	<div class="panel-body" id="main">

<!-- jobtype fields -->
	<input type="button" name="btnAdd" id="btAdd" value="Neuen Dienst hinzufügen" class="btn btn-success" />
    <input type="button" name="btnRemove" id="btRemove" value="Letzten Dienst löschen" class="btn btn-danger" /><br />
    <input type="hidden" name="counter" id="counter" value="0" /><br>
    <div id="container" class="container">
		<!-- new fields will be shown here -->   

    </div>
	
</div>
	