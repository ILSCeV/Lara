<!DOCTYPE html>
<html>
	<head>
		<title>Lara VedSt | @yield('title', 'VedSt Default Title')</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   	 	<meta name="viewport" content="width=device-width, initial-scale=1">
		{{ HTML::script('js/jquery-2.1.1.js') }}
		{{ HTML::script('js/bootstrap.js') }}
		{{ HTML::script('js/vedst-scripts.js') }}

		{{ HTML::style('css/bootstrap-paper.css') }}
		{{ HTML::style('css/font-awesome.min.css') }}
		{{ HTML::style('css/vedst.css') }}
		{{ HTML::style('css/print.css', array('media' => 'print')) }}

		<link rel="shortcut icon" href="/favicon-96x96.png" type="image/png" />
		<link rel="icon" href="/favicon-96x96.png" type="image/png" />

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