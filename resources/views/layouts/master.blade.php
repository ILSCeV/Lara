<!DOCTYPE html>
<html>
	<head>
		<title>Lara | @yield('title', 'Default Title')</title>
        <meta charset="UTF-8">
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
        <meta http-equiv="Cache-control" content="no-cache">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="{{ asset(WebpackBuiltFiles::$cssFiles['lara']) }}">
        {{--
        <link rel="stylesheet" type="text/css" href="{{ mix('/static.css') }}">
        --}}
    	<link rel="shortcut icon" type="image/png" href="{{ asset('/favicon-48x48.png') }}">

        @yield('moreStylesheets')
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
	</head>

    <body @if(Session::get("applocale","de")  == "pirate")
              style="background-image:url( {{asset('/background-pirate.jpg')}} ) !important;
                     background-size:initial;
                     background-position:center;"
          @endif>

        <!-- Set the language to the same as the server -->
        <script type="text/javascript">
            localStorage.setItem('language', "{{ Session::get("applocale","de") }}");
        </script>

		<header class="navigation">
			@include('partials.navigation')
		</header>

		<div class="message" id="centerpoint">
			@include('partials.message')
		</div>

        <section class="container-fluid">
            @yield('content')
        </section>

        <!-- Back to Top button -->
        <a id="back-to-top"
           href="#"
           class="btn btn-primary btn-lg back-to-top hidden-print d-md-none d-lg-none d-sm-block d-block"
           role="button"
           title="{{ trans("mainLang.backToTop")  }}"
           data-toggle="tooltip"
           data-placement="right">
            <i class="fas fa-chevron-up"></i>
        </a>

        <br>

     	<section class="footer">
            @include('partials.footer')
        </section>

        <script> var enviroment = '{{App::environment()}}'; </script>
        <script src="{{asset(WebpackBuiltFiles::$jsFiles['lara'])}}" ></script>
        @yield('moreScripts')
        {{--
            <script src="{{ mix('/manifest.js') }}"></script>
            <script src="{{ mix('/vendor.js') }}"></script>
            <script src="{{ mix('/static.js') }}"></script>
            <script src="{{ mix('/lara.js') }}"></script>
        --}}
    </body>
</html>
