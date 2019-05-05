<nav class="navbar navbar-expand-md navbar-light bg-light fixed-top p-2x nav-max-height">
    <div class="navbar-header">
{{-- HAMBURGER / Mobile only --}}
        <button type="button"
                class="navbar-toggler collapsed btn btn-primary"
                data-toggle="collapse"
                data-target="#navbar"
                aria-expanded="false"
                aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            &#x2630;
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
        <ul class="nav navbar-nav mr-auto mt-2 mt-lg-0">

{{-- MONTH VIEW / public --}}
            <li class="nav-item" ><a class="nav-link" href="{{ asset('/calendar/month') }}">{{ trans('mainLang.month') }}</a></li>

{{-- WEEK VIEW / public --}}
            <li class="nav-item" ><a class="nav-link" href="{{ asset('/calendar/week') }}">{{ trans('mainLang.week') }}</a></li>

{{-- DAY VIEW / public --}}
            <li class="nav-item" ><a class="nav-link" href="{{ asset('/calendar/today') }}">{{ trans('mainLang.today') }}</a></li>

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

        <ul class="nav navbar-nav">
            <li class="nav-item pr-2">
                @php
                if(\Lara\utilities\ViewUtility::isLightMode()){
                    $viewModeRoute = route('viewMode.switch','dark');
                } else {
                    $viewModeRoute = route('viewMode.switch','light');
                }
                @endphp
                <a href="{{ $viewModeRoute }}" id="darkmodeToggle" class="btn btn-sm @if(\Lara\utilities\ViewUtility::isLightMode()) btn-secondary @else btn-primary @endif nav-link">
                    @if(\Lara\utilities\ViewUtility::isLightMode())
                        {{ trans('mainLang.dark') }}
                    @else
                        {{ trans('mainLang.light') }}
                    @endif
                </a>
            </li>
{{-- AUTHENTICATION --}}
            @auth

{{-- CREATE BUTTONS / members only --}}
    {{-- Desktop version --}}
                <li class="d-none d-md-block d-lg-block nav-item">
                    <div class="btn-group">
                        <button type="button"
                                class="btn btn-sm btn-primary nav-link text-white mr-2"
                                data-toggle="dropdown">
                            &nbsp;&nbsp;&nbsp;&nbsp;+&nbsp;&nbsp;&nbsp;&nbsp;
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ URL::route('event.create') }}">{{ trans('mainLang.createNewEvent') }}</a>
                            <a class="dropdown-item" href="{{ URL::route('survey.create') }}">{{ trans('mainLang.createNewSurvey') }}</a>
                        </div>
                    </div>
                </li>

    {{-- Mobile version --}}
                <li class="nav-item d-block d-sm-none">
                    <a href="{{ URL::route('event.create') }}"
                       class="btn btn-sm btn-primary d-block d-sm-none text-center nav-link text-white">
                        {{ trans('mainLang.createNewEvent') }}
                    </a>
                </li>

                <li class="nav-item d-block d-sm-none">
                    <a href="{{ URL::route('survey.create') }}"
                       class="btn btn-sm btn-primary d-block d-sm-none text-center nav-link text-white">
                        {{ trans('mainLang.createNewSurvey') }}
                    </a>
                </li>

{{-- MEMBER INFO / members only --}}
                <li class="nav-item">
                    <span data-toggle="tooltip"
                          data-placement="bottom"
                          title="{{ trans('mainLang.userPersonalPage') }}">
                              <strong>
                                  <a class="nav-link" href="{{route('user.personalpage')}}">
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
                                   @php
                                       $attributes = Lara\Status::style(Auth::user()->status);
                                   @endphp
                                       <i class="{{ $attributes["status"]}}"
                                          style="{{ $attributes["style"] }}"
                                          data-toggle="tooltip"
                                          data-placement="bottom"
                                          title="{{ Lara\Status::localizeCurrent() }}"></i>
                                   </a>
                               </strong>
                    </span>
                </li>
                <li  class="nav-item btn-group">
                    {!! Form::open(array('url' => 'logout',
                                         'method' => 'POST',
                                         'class'=>'form-inline')) !!}
                        <div class="navbar-form">
                            &nbsp;&nbsp;

                            <br class="d-block d-sm-none">
                            <br class="d-block d-sm-none">
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

          </ul>

        </div>
</nav>
