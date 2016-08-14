<!DOCTYPE html>
<html>

	<head>
		<title>Lara | @yield('title', 'VedSt Default Title')</title>

		<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
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

  <body>
		<header class="navigation">
			@include('partials.navigation')
		</header>

		<div class="message" id="centerpoint">
			@include('partials.message')
		</div>

    <section class="container containerNopadding">
        @yield('content')
    </section>

 	<footer class="container footer" id="footer">
        <hr class="hidden-print">
        <span class="col-xs-12 col-sm-4 col-md-4 text-dark-grey" style="text-align: center;">
            <small><a href="mailto:maxim.drachinskiy@bc-studentenclub.de"> {{ trans('mainLang.notWorkingMail',['Name' => 'Maxim']) }} </a></small>
        </span>
        <span class="col-xs-6 col-sm-4 col-md-4 text-dark-grey" style="text-align: center;">
            @if(File::exists("gitrevision.txt"))
                <small>{{File::get("gitrevision.txt")}}</small>
            @else
                <small>&nbsp;</small>
            @endif
        </span>
        <span class="col-xs-6 col-sm-4 col-md-4 text-dark-grey" style="text-align: center;">
            <small><a href="http://github.com/4D44H/lara-vedst">{{ trans('mainLang.moreInfosProjectsite') }}</a>.
            </small>
        </span>
        <!-- Button for switching language -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                {{ Config::get('languages')[App::getLocale()] }}
            </a>
            <ul class="dropdown-menu">
                @foreach (Config::get('languages') as $lang => $language)
                    @if ($lang != App::getLocale())
                        <li>
                            <a href="{{ route('lang.switch', $lang) }}">{{$language}}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
        <br class="visible-xs visible-sm">
        <br>
        <br>
	</footer>

    <script src="{{ asset('/js/jquery-2.1.3.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('/js/vedst-scripts.js') }}"></script>
        <script src="{{ asset('/js/bin/bundle.js') }}"></script>
    <script src="{{ asset('/js/bootstrap-select.min.js') }}"></script>
	@yield('moreScripts')
  </body>
</html>
