<!DOCTYPE html>
<html>

	<head>
		<title>Lara | @yield('title', 'VedSt Default Title')</title>
        <meta charset="UTF-8">
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
        <meta http-equiv="Cache-control" content="no-cache">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="{{ mix('/lara.css') }}">
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

    <!-- Back to Top button -->
    <a id="back-to-top" 
       href="#" 
       class="btn btn-primary btn-lg back-to-top hidden-print hidden-md hidden-lg" 
       role="button" 
       title="{{ trans("mainLang.backToTop")  }}" 
       data-toggle="tooltip" 
       data-placement="right">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>

    <br>
 	<footer class="navbar-default navbar-static-bottom hidden-print" id="footer">
        <div class="container">
            <br>
            <span class="col-xs-12 col-sm-12 col-md-4 text-dark-grey" style="text-align: center;">
                <small><a href="mailto:lara@il-sc.de"> {{ trans('mainLang.notWorkingMail',['Name' => 'Lara']) }} </a></small>
            </span>
            <span class="col-xs-12 col-sm-12 col-md-4 text-dark-grey" style="text-align: center;">
                <small>
                    {{ File::exists("gitrevision.txt") ? File::get("gitrevision.txt") : "&nbsp;" }}
                </small>
            </span>
            <span class="col-xs-12 col-sm-12 col-md-4 text-dark-grey" style="text-align: center;">
                <small><a href="https://github.com/ILSCeV/Lara">{{ trans('mainLang.moreInfosProjectsite') }}</a>
                </small>
            </span>
            <br class="visible-xs visible-sm">
            <br class="visible-xs visible-sm">
            <br>
            <br>
        </div>
	</footer>
        <script> var enviroment = '{{App::environment()}}'; </script>
        <script src="{{ mix('/manifest.js') }}"></script>
        <script src="{{ mix('/vendor.js') }}"></script>
        <script src="{{ mix('/lara.js') }}"></script>
    </body>
</html>
