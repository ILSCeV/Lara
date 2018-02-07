<nav class="navbar navbar-default navbar-static-top navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
        <button type="button"
                class="navbar-toggle collapsed"
                data-toggle="collapse"
                data-target="#navbar"
                aria-expanded="false"
                aria-controls="navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

{{-- LARA LOGO --}}
        <a class="navbar-brand" href="{{ asset('/') }}">
            @if (App::environment('development'))
                <img id="nav-logo-field" src="{{  asset('/logos/lara-logo-dev.png') }}" alt="LARA dev">
            @elseif (App::environment('berta'))
                <img id="nav-logo-field" src="{{  asset('/logos/lara-logo-berta.png') }}" alt="BERTA">
            @else
               <img id="nav-logo-field" src="{{  asset('/logos/lara-logo-prod.png') }}" alt="LARA">
            @endif
        </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
{{-- DAY VIEW / public --}}
            <li><a href="{{ asset('/calendar/today') }}">{{ trans('mainLang.today') }}</a></li>
{{-- MONTH VIEW / public --}}
            <li><a href="{{ asset('/calendar/month') }}">{{ trans('mainLang.month') }}</a></li>

{{-- WEEK VIEW / public --}}
            <li><a href="{{ asset('/calendar/week') }}">{{ trans('mainLang.week') }}</a></li>

{{-- MEMBER STATISTICS / members only --}}
            @auth
                <li><a href="{{ action('StatisticsController@showStatistics') }}">{{ trans('mainLang.statisticalEvaluation') }}</a></li>
            @endauth
{{-- SETTINGS (GEAR ICON) --}}
            <li class="dropdown">
                <a href="#"
                 class="dropdown-toggle"
                 data-toggle="dropdown"
                 role="button" aria-expanded="false">
                    <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">

{{-- MANAGEMENT: shift types / marketing, section management or admins only --}}

                @if(Session::get('userGroup') == 'marketing'
                 OR Session::get('userGroup') == 'clubleitung'
                 OR Session::get('userGroup') == 'admin')
                    <li>
                        <a href="{{ asset('shiftType') }}">
                            <i class="fa fa-magic" aria-hidden="true"></i>
                            {{ trans('mainLang.manageShiftType') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{route('template.overview')}}">
                            <i class="fa fa-magic" aria-hidden="true"></i>
                            {{ trans('mainLang.manageTemplates')  }}
                        </a>
                    </li>
                @endif


{{-- LARA LOGS / section management or admins only --}}
                @if(Session::get('userGroup') == 'clubleitung'
                 OR Session::get('userGroup') == 'admin')
                    <li><a href="{{ asset('/logs') }}">Logs</a></li>
                @endif



{{-- ICal feed links
Disabling iCal until fully functional.

                    <li><a href="#" name="icalfeeds"><i class="fa fa-calendar" aria-hidden="true"></i> {{ trans('mainLang.icalfeeds') }}</a></li>

--}}


{{-- LANGUAGE SWITCHER / public --}}
                @foreach (Config::get('languages') as $lang => $language)
                    <li class="languageSwitcher"><a href="{{ route('lang.switch', $lang) }}" data-language="{{$lang}}"><i class="fa fa-globe" aria-hidden="true"></i></i> {{$language}}</a></li>
                @endforeach

                </ul>
            </li>


{{-- LARA ADMIN PANEL / admins only --}}
            @if(\Lara\Utilities::requirePermission('admin'))
                <li class="dropdown">
                    <a href="#"
                       class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button" aria-expanded="false">
                        <i class="fa fa-diamond" aria-hidden="true"></i>&nbsp;<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ asset('section') }}">{{ trans('mainLang.manageSections') }}</a>
                        </li>
                        <li>
                            <a href="{{route("lara.update")}}">
                                <i class="fa fa-chevron-circle-up" aria-hidden="true"></i>
                                Lara update </a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <span class="col-xs-1 visible-xs">&nbsp;</span>
            <div class="col-xs-10 col-sm-12 col-md-12 no-margin no-padding">

{{-- AUTHENTICATION --}}
                @if(Session::has('userId'))

{{-- CREATE BUTTONS / members only --}}
    {{-- Desktop version --}}
                    <li style="padding-top:5px" class="btn-group hidden-xs">
                        <div style="padding-top:2px" class="btn-group">
                            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">+</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ URL::route('event.create') }}">{{ trans('mainLang.createNewEvent') }}</a></li>
                                <li><a href="{{ URL::route('survey.create') }}">{{ trans('mainLang.createNewSurvey') }}</a></li>
                            </ul>
                        </div>
                    </li>

    {{-- Mobile version --}}
                    <a href="{{ URL::route('event.create') }}"
                       class="btn btn-sm btn-primary visible-xs centered">
                        {{ trans('mainLang.createNewEvent') }}
                    </a>

                    <br class="visible-xs">

                    <a href="{{ URL::route('survey.create') }}"
                       class="btn btn-sm btn-primary visible-xs centered">
                        {{ trans('mainLang.createNewSurvey') }}
                    </a>

{{-- MEMBER INFO / members only --}}
                    <li style="padding-top: 5px;" class="btn-group testleft ">
                        {!! Form::open(array('url' => 'logout',
                                            'method' => 'POST',
                                            'class'=>'form-horizontal')) !!}
                            <div class="navbar-form">
                                &nbsp;&nbsp;
                                @if     ( Session::get('userStatus') === 'candidate' )
                                    <i class="fa fa-adjust"
                                       style="color:yellowgreen;"
                                       data-toggle="tooltip"
                                       data-placement="bottom"
                                       title="{{ trans('mainLang.candidate') }}"></i>
                                @elseif ( Session::get('userStatus') === 'veteran' )
                                    <i class="fa fa-star"
                                       style="color:gold;"
                                       data-toggle="tooltip"
                                       data-placement="bottom"
                                       title="{{ trans('mainLang.veteran') }}"></i>
                                @elseif ( Session::get('userStatus') === 'resigned' )
                                    <i class="fa fa-star-o"
                                       style="color:gold;"
                                       data-toggle="tooltip"
                                       data-placement="bottom"
                                       title="{{ trans('mainLang.ex-member') }}"></i>
                                @elseif ( Session::get('userStatus') === 'member')
                                    <i class="fa fa-circle"
                                       style="color:forestgreen;"
                                       data-toggle="tooltip"
                                       data-placement="bottom"
                                       title="{{ trans('mainLang.active') }}"></i>
                                @endif
                                &nbsp;
                                <strong>
                                    <span data-toggle="tooltip"
                                          data-placement="bottom"
                                          title="
                                            @if(Session::get('userGroup') == 'marketing')
                                                {{ Session::get('userClub') . " / Marketing" }}
                                            @elseif (Session::get('userGroup') == 'clubleitung')
                                                {{ Session::get('userClub') . " / Clubleitung" }}
                                            @elseif (Session::get('userGroup') == 'admin')
                                                {{ Session::get('userClub') . " / Admin" }}
                                            @else
                                                {{ Session::get('userClub') }}
                                            @endif
                                          ">
                                        {{ Session::get('userName') }}
                                    </span>
                                </strong>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                {!! Form::submit( Lang::get('mainLang.logOut'),
                                                  array('class' => 'btn btn-default btn-sm pull-right',
                                                        'name'  => 'logout') ) !!}
                            </div>
                        {!! Form::close() !!}
                    </li>

                @else

{{-- LOGIN FORM / public --}}
                    <li>
                        @include('partials/login')
                    </li>

                @endif
            </div>
            <span class="col-xs-1 visible-xs">&nbsp;</span>
          </ul>

        </div>
    </div>
</nav>
