<!DOCTYPE html>
<html>

	<head>
		<title>Lara | @yield('title', 'VedSt Default Title')</title>
        <meta charset="UTF-8">
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
        <meta http-equiv="Cache-control" content="no-cache">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="{{ asset('/css/bootstrap-select.min.css') }}">
        <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/bootstrap-bootswatch-paper.min.css') }}" />
        <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/font-awesome.min.css') }}" />
        <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/vedst.css') }}" />
        <link rel="stylesheet" media="print" type="text/css" href="{{ asset('/css/print.css') }}" />
    	<link rel="shortcut icon" type="image/png" href="{{ asset('/favicon-48x48.png') }}">
        @yield('moreStylesheets')
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
	</head>

    <body @if(Session::get("applocale","de")  == "pirate") style="background-image:url( {{asset('/background-pirate.jpg')}} ) !important; background-size:initial; background-position:center;"  @endif>
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

    <section class="container containerNopadding">
        @yield('content')
    </section>

    <br>
 	<footer class="navbar-default navbar-static-bottom" id="footer">
        <div class="container">
            <br>
            <span class="col-xs-12 col-sm-12 col-md-4 text-dark-grey" style="text-align: center;">
                <small><a href="mailto:maxim.drachinskiy@bc-studentenclub.de"> {{ trans('mainLang.notWorkingMail',['Name' => 'Maxim']) }} </a></small>
            </span>
            <span class="col-xs-12 col-sm-12 col-md-4 text-dark-grey" style="text-align: center;">
                @if(File::exists("gitrevision.txt"))
                    <small>{{File::get("gitrevision.txt")}}</small>
                @else
                    <small>&nbsp;</small>
                @endif
            </span>
            <span class="col-xs-12 col-sm-12 col-md-4 text-dark-grey" style="text-align: center;">
                <small><a href="https://github.com/ILSCeV/Lara">{{ trans('mainLang.moreInfosProjectsite') }}</a>.
                </small>
            </span>
            <br class="visible-xs visible-sm">
            <br class="visible-xs visible-sm">
            <br>
            <br>
        </div>
	</footer>

    <script src="{{ mix('js/app.js') }}"></script>
        @if(Session::get("applocale","de")  == "pirate")
            <script src="{{ asset('/js/pirateTranslator.js') }}"></script>
        @endif
	@yield('moreScripts')
  </body>
</html>
