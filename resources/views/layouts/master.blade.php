<!DOCTYPE html>
<html>
	<head>
		<title>Lara VedSt | @yield('title', 'VedSt Default Title')</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   	 	<meta name="viewport" content="width=device-width, initial-scale=1">
		{!! HTML::style('css/bootstrap-bootswatch-paper.min.css') !!}
		{!! HTML::style('css/font-awesome.min.css') !!}
		{!! HTML::style('css/vedst.css') !!}
		{!! HTML::style('css/print.css', array('media' => 'print')) !!}

		<link rel="shortcut icon" href="/favicon-96x96.png" type="image/png" />
		<link rel="icon" href="/favicon-96x96.png" type="image/png" />


<!-- TESTING AJAX -->

<style type="text/css">

  .form-signin {
    max-width: 330px;
    padding: 15px;
    margin: 0 auto;
  }
  .form-signin .form-signin-heading,
  .form-signin .checkbox {
    margin-bottom: 10px;
  }
  .form-signin .checkbox {
    font-weight: normal;
  }
  .form-signin .form-control {
    position: relative;
    height: auto;
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
    padding: 10px;
    font-size: 16px;
  }
  .form-signin .form-control:focus {
    z-index: 2;
  }
  .form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }
</style>

<!-- END -->

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
		
    {!! HTML::script('js/jquery-2.1.3.min.js') !!}
    {!! HTML::script('js/bootstrap.js') !!}
		{!! HTML::script('js/vedst-scripts.js') !!}
    </body>
</html>