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

    	{{-- Filter events from club/cafe --}}
		<script type="text/javascript">
		    $(document).ready(function() {			    
			    $('#bc-Club').change(function() {
			        if($(this).prop("checked")) {
			            $('.bc-Club').show();
			        } else {
			            $('.bc-Club').hide();  
			        }         
			    });
			});

			$(document).ready(function() {			    
			    $('#bc-Café').change(function() {
			        if($(this).prop("checked")) {
			            $('.bc-Café').show();
			        } else {
			            $('.bc-Café').hide();  
			        }			              
			    });
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