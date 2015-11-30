<!DOCTYPE html>
<html>

	<head>
		<title>Lara | @yield('title', 'VedSt Default Title')</title>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/bootstrap-bootswatch-paper.min.css') }}" />
    <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/vedst.css') }}" />
    <link rel="stylesheet" media="print" type="text/css" href="{{ asset('/css/print.css') }}" />

		<link rel="shortcut icon" type="image/png" href="{{ asset('/favicon-48x48.png') }}">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
		
    <!-- Scripts -->
    <script src="{{ asset('/js/jquery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('/js/vedst-scripts.js') }}"></script>
  </body>
</html>