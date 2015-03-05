<!DOCTYPE html>
<html>
	<head>
		<title>Lara Vedst: @yield('title', 'VedSt Default Title')</title>
		<meta charset="utf-8">
   	 	<meta name="viewport" content="width=device-width, initial-scale=1">

		{{ HTML::style('css/bootstrap-paper.css') }}
		{{ HTML::script('js/jquery-2.1.1.js') }}
		{{ HTML::script('js/bootstrap.js') }}
		{{ HTML::style('css/font-awesome.min.css') }}
		{{ HTML::style('css/vedst.css') }}
		{{ HTML::style('css/print.css', array('media' => 'print')) }}

	</head>
    <body>
		<div class="navigation">
			@include('partials.navigation')
		</div>

    	{{-- Automativally close messages after 4 seconds (4000 milliseconds). M. --}}
    	
	    <script type="text/javascript">
			window.setTimeout(function() {
			    $(".message").fadeTo(1000, 0).slideUp(500, function(){
			        $(this).alert('close'); 
			    });
			}, 4000);
			$(function(){
				$('.moreless').click(function(e) {
					$(this).parent().children('.more').toggleClass('moreshow');
					window.location.hash="navbar";
				});
			});

    	</script>
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