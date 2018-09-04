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
                <img id="nav-logo-field" src="{{  asset('/logos/lara-logo-dev.png') }}" height="48" alt="LARA dev">
            @elseif (App::environment('berta'))
                <img id="nav-logo-field" src="{{  asset('/logos/lara-logo-berta.png') }}" height="48" alt="BERTA">
            @else
               <img id="nav-logo-field" src="{{  asset('/logos/lara-logo-prod.png') }}" height="48" alt="LARA">
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

                    @auth
                        @noLdapUser
                        <li>
                            <a href="{{route('password.change')}}">
                                <i class="fa fa-key fa-rotate-90" aria-hidden="true"></i>
                                {{ trans('auth.changePassword') }}
                            </a>
                        </li>

                        <li role="separator" class="divider"></li>
                        @endnoLdapUser
                    @endauth
{{-- MANAGEMENT: shift types / marketing, section management or admins only --}}

                @is(Roles::PRIVILEGE_MARKETING, Roles::PRIVILEGE_CL, Roles::PRIVILEGE_ADMINISTRATOR)
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
                    @is(Roles::PRIVILEGE_CL, Roles::PRIVILEGE_ADMINISTRATOR)
                    <li>
                        <a href="{{ route('user.index') }}">
                            <i class="fa fa-users" aria-hidden="true"> </i>
                            {{ trans('users.userManagement') }}
                        </a>
                    </li>
                    <li>
                       <a href="{{ asset('section') }}">
                           <i class="fa fa-university" aria-hidden="true"></i>
                           {{ trans('mainLang.manageSections') }}
                       </a>
                    </li>
                    @endis
                    <li role="separator" class="divider"></li>
                @endis

                {{-- LARA LOGS / section management or admins only --}}
                @is(Roles::PRIVILEGE_ADMINISTRATOR)
                    <li><a href="{{ asset('/logs') }}">Logs</a></li>
                    <li role="separator" class="divider"></li>
                @endis



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
            @if(\Lara\Utilities::requirePermission(Roles::PRIVILEGE_ADMINISTRATOR))
                <li class="dropdown">
                    <a href="#"
                       class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button" aria-expanded="false">
                        <i class="fa fa-diamond" aria-hidden="true"></i>&nbsp;<span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
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
                @auth

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
                    <li style="padding-top: 8px;" class="btn-group">
                        {!! Form::open(array('url' => 'logout',
                                            'method' => 'POST',
                                            'class'=>'form-horizontal')) !!}
                            <div class="navbar-form">
                                &nbsp;&nbsp;
                                @php
                                    $attributes = Lara\Status::style(Auth::user()->status);
                                @endphp
                                <i class="{{ $attributes["status"]}}"
                                   style="{{ $attributes["style"] }}"
                                   data-toggle="tooltip"
                                   data-placement="bottom"
                                   title="{{ Lara\Status::localizeCurrent() }}"></i>
                                &nbsp;
                                <strong>
                                    <span data-toggle="tooltip"
                                          data-placement="bottom"
                                          title="{{ trans('mainLang.userPersonalPage') }}">
                                            <a href="{{route('user.personalpage')}}" >
                                                {{ Auth::user()->name }}
                                                @is(Roles::PRIVILEGE_ADMINISTRATOR)
                                                    ({{ Auth::user()->section->title . " / Admin" }})
                                                @elseis(Roles::PRIVILEGE_CL)
                                                    ({{ Auth::user()->section->title . " / Clubleitung" }})
                                                @elseis(Roles::PRIVILEGE_MARKETING)
                                                    ({{ Auth::user()->section->title . " / Marketing" }})
                                                @else
                                                    ({{ Auth::user()->section->title }})
                                                @endis
                                            </a>
                                    </span>
                                </strong>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <br class="visible-xs">
                                <br class="visible-xs">
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

                @endauth
            </div>
            <span class="col-xs-1 visible-xs">&nbsp;</span>
          </ul>

        </div>
    </div>
</nav>
