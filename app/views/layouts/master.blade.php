<!DOCTYPE html>
<html>
	<head>
		<title>Lara VedSt | @yield('title', 'VedSt Default Title')</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   	 	<meta name="viewport" content="width=device-width, initial-scale=1">

		{{ HTML::style('css/bootstrap-paper.css') }}
		{{ HTML::script('js/jquery-2.1.1.js') }}
		{{ HTML::script('js/bootstrap.js') }}
		{{ HTML::style('css/font-awesome.min.css') }}
		{{ HTML::style('css/vedst.css') }}
		{{ HTML::style('css/print.css', array('media' => 'print')) }}

		<link rel="shortcut icon" href="/favicon-96x96.png" type="image/png" />
		<link rel="icon" href="/favicon-96x96.png" type="image/png" />
		
		{{-- Automatically close messages after 4 seconds (4000 milliseconds). M. --}}
		<script type="text/javascript">			
			window.setTimeout(function() {
			    $(".message").fadeTo(1000, 0).slideUp(500, function(){
			        $(this).alert('close'); 
			    });
			}, 4000);
		</script>

		{{-- Show/hide more button for infos --}}
		<script type="text/javascript">
			$(function(){
				$('.moreless').click(function(e) {
					$(this).parent().children('.more').toggleClass('moreshow');
					window.location.hash="navbar";
				});
			});
		</script>

		{{-- Show/hide comments --}}
		<script type="text/javascript">
			$(function(){
				$('.showhide').click(function(e) {
					$(this).parent().parent().next().children().children('.hide').toggleClass('show');
					window.location.hash="";
				});
			});
    	</script>

    	{{-- Filter events from club/cafe, using browser local storage if enabled to save state --}}
		<script type="text/javascript">
		    $(document).ready(function() {			    
			    $('#bc-Club').change(function() {
			        if($(this).prop("checked")) {
			            $('.bc-Club').show();
			            if(typeof(Storage) !== "undefined") {
			            	localStorage.filterClub = "show";
			            } 
			        } else {
			            $('.bc-Club').hide(); 
			           	if(typeof(Storage) !== "undefined") {
			            	localStorage.filterClub = "hide";
			            } 
			        }         
			    });
			});

			$(document).ready(function() {			    
			    $('#bc-Café').change(function() {
			        if($(this).prop("checked")) {
			            $('.bc-Café').show();
			            if(typeof(Storage) !== "undefined") {
			            	localStorage.filterCafe = "show";
			            }			        
			        } else {
			            $('.bc-Café').hide();  
			           	if(typeof(Storage) !== "undefined") {
			            	localStorage.filterCafe = "hide";
			            } 
			        }			              
			    });
			});
			
			$(document).ready(function() {
				if(typeof(Storage) !== "undefined") {
					if (localStorage.filterClub == "hide") {
			        	$("#bc-Club").prop('checked', false);
			        	$('.bc-Club').hide();		        	
			        } else {
			        	$("#bc-Club").prop('checked');
			        	$('.bc-Club').show();			        	
			        }

					if (localStorage.filterCafe == "hide") {
			        	$("#bc-Café").prop('checked', false);
			        	$('.bc-Café').hide();
			        } else {
			        	$("#bc-Café").prop('checked');
			        	$('.bc-Café').show();			        	
			        }

			    } else {
			    	if ( "{{ Session::get('clubFilter') }}" === "bc-Café" ) {
			        	$("#bc-Club").prop('checked', false);
			        	$('.bc-Club').hide();
			    	} else if ( "{{ Session::get('clubFilter') }}" === "bc-Club" ) {
			        	$("#bc-Café").prop('checked', false);
			        	$('.bc-Café').hide();
			    	} else {
			        	$("#bc-Café").prop('checked');
			        	$('.bc-Café').show();
			        	$("#bc-Club").prop('checked');
			        	$('.bc-Club').show();			    		
			    	}
			    }
			});
		</script>

	</head>
    <body>
		<div class="navigation">
			@include('partials.navigation')
		</div>

		<div class="message" id="centerpoint">
			@include('partials.message')
		</div>

        <div class="container">
            @yield('content')
        </div>

	 	<div class="footer">
			@include('partials.footer')
		</div>
    </body>
</html>