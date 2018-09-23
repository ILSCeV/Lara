<nav class="navbar navbar-expand-sm navbar-light bg-lightfixed-top">
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

            <li class="nav-item" ><a class="nav-link" href="{{ asset('/calendar/today') }}">{{ trans('mainLang.today') }}</a></li>
{{-- MONTH VIEW / public --}}
            <li class="nav-item" ><a class="nav-link" href="{{ asset('/calendar/month') }}">{{ trans('mainLang.month') }}</a></li>

{{-- WEEK VIEW / public --}}
            <li class="nav-item" ><a class="nav-link" href="{{ asset('/calendar/week') }}">{{ trans('mainLang.week') }}</a></li>

{{-- MEMBER STATISTICS / members only --}}
            @auth
                <li class="nav-item" ><a class="nav-link" href="{{ action('StatisticsController@showStatistics') }}">{{ trans('mainLang.statisticalEvaluation') }}</a></li>
            @endauth

{{-- LANGUAGE SWITCHER / public --}}
        <li class="dropdown nav-item">
            <a href="#"
             class="dropdown-toggle nav-link"
             data-toggle="dropdown"
             role="button" aria-expanded="false">
                <i class="fa fa-language"></i>&nbsp;<span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                @foreach (Config::get('languages') as $lang => $language)
                    <li class="languageSwitcher dropdown-item"><a class="nav-link" href="{{ route('lang.switch', $lang) }}" data-language="{{$lang}}"><i class="fa fa-globe" aria-hidden="true"></i></i> {{$language}}</a></li>
                @endforeach
            </ul>
        </li>

{{-- SETTINGS (GEAR ICON) / marketing, section management or admins only --}}
        @is(Roles::PRIVILEGE_MARKETING, Roles::PRIVILEGE_CL, Roles::PRIVILEGE_ADMINISTRATOR)
            <li class="dropdown nav-item">
                <a href="#"
                 class="dropdown-toggle nav-link"
                 data-toggle="dropdown"
                 role="button" aria-expanded="false">
                    <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                </a>

{{-- SHIFT TYPES AND TEMPLATE MANAGEMENT / marketing, section management or admins only --}}
                <ul class="dropdown-menu" role="menu">
                    <li class="dropdown-item">
                        <a class="nav-link" href="{{ asset('shiftType') }}">
                            <i class="fa fa-magic" aria-hidden="true"></i>
                            {{ trans('mainLang.manageShiftType') }}
                        </a>
                    </li>
                    <li class="dropdown-item">
                        <a class="nav-link" href="{{route('template.overview')}}">
                            <i class="fa fa-magic" aria-hidden="true"></i>
                            {{ trans('mainLang.manageTemplates')  }}
                        </a>
                    </li>

{{-- USER AND SECTION MANAGEMENT / section management or admins only --}}
                    @is(Roles::PRIVILEGE_CL, Roles::PRIVILEGE_ADMINISTRATOR)
                    <li class="dropdown-item">
                        <a class="nav-link" href="{{ route('user.index') }}">
                            <i class="fa fa-users" aria-hidden="true"> </i>
                            {{ trans('users.userManagement') }}
                        </a>
                    </li>
                    <li class="dropdown-item">
                       <a class="nav-link" href="{{ asset('section') }}">
                           <i class="fa fa-university" aria-hidden="true"></i>
                           {{ trans('mainLang.manageSections') }}
                       </a>
                    </li>
                    @endis
                @endis

{{-- LARA ADMINISTRATION / admins only --}}
                @is(Roles::PRIVILEGE_ADMINISTRATOR)
                    <li role="separator" class="divider dropdown-item"></li>
                    <li class="dropdown-item">
                        <a class="nav-link" href="{{ asset('/logs') }}">
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                            Logs
                        </a>
                    </li>
                    <li role="separator" class="divider dropdown-item"></li>
                    <li class="dropdown-item">
                        <a class="nav-link" href="{{route("lara.update")}}">
                            <i class="fa fa-chevron-circle-up" aria-hidden="true"></i>
                            Lara update </a>
                    </li>
                @endis

{{-- ICal feed links
Disabling iCal until fully functional.

                    <li><a href="#" name="icalfeeds"><i class="fa fa-calendar" aria-hidden="true"></i> {{ trans('mainLang.icalfeeds') }}</a></li>

--}}
                </ul>
            </li>
        </ul>

        <ul class="nav navbar-nav ml-auto">
            <span class="col-xs-1 d-block.d-sm-none">&nbsp;</span>
            <div class="col-xs-10 col-sm-12 col-md-12 no-margin no-padding">

{{-- AUTHENTICATION --}}
                @auth

{{-- CREATE BUTTONS / members only --}}
    {{-- Desktop version --}}
                    <li style="padding-top:5px" class="btn-group d-none nav-item">
                        <div style="padding-top:2px" class="btn-group">
                            <a class="btn btn-primary dropdown-toggle nav-link" data-toggle="dropdown" href="#">+</a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-item" ><a href="{{ URL::route('event.create') }}">{{ trans('mainLang.createNewEvent') }}</a></li>
                                <li class="dropdown-item" ><a href="{{ URL::route('survey.create') }}">{{ trans('mainLang.createNewSurvey') }}</a></li>
                            </ul>
                        </div>
                    </li>

    {{-- Mobile version --}}
                    <a href="{{ URL::route('event.create') }}"
                       class="btn btn-sm btn-primary d-block.d-sm-none centered">
                        {{ trans('mainLang.createNewEvent') }}
                    </a>

                    <br class="d-block.d-sm-none">

                    <a href="{{ URL::route('survey.create') }}"
                       class="btn btn-sm btn-primary d-block.d-sm-none centered">
                        {{ trans('mainLang.createNewSurvey') }}
                    </a>

{{-- MEMBER INFO / members only --}}
                    <li style="padding-top: 8px;" class="btn-group nav-item">
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
                                <br class="d-block.d-sm-none">
                                <br class="d-block.d-sm-none">
                                {!! Form::submit( Lang::get('mainLang.logOut'),
                                                  array('class' => 'btn btn-secondary btn-sm float-right',
                                                        'name'  => 'logout') ) !!}
                            </div>
                        {!! Form::close() !!}
                    </li>

                @else

{{-- LOGIN FORM / public --}}
                    <li class="nav-item">
                        @include('partials/login')
                    </li>

                @endauth
            </div>
            <span class="col-xs-1 d-block.d-sm-none">&nbsp;</span>
          </ul>

        </div>
    </div>
</nav>
