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
            <img id="nav-logo-field" src="{{  asset('/logo.png') }}" alt="LARA">
        </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">

{{-- MONTH VIEW / public --}}
            <li><a href="{{ asset('/calendar/month') }}">{{ trans('mainLang.month') }}</a></li>

{{-- WEEK VIEW / public --}}
            <li><a href="{{ asset('/calendar/week') }}">{{ trans('mainLang.week') }}</a></li>

{{-- MEMBER STATISTICS / members only --}}
            @if(Session::has('userId'))
                <li><a href="{{ asset('/statistics') }}">{{ trans('mainLang.statisticalEvaluation') }}</a></li>
            @endif
            
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
                    <li><a href="{{ asset('jobtype') }}">{{ trans('mainLang.manageJobType') }}</a></li>
                @endif


{{-- LARA LOGS / section management or admins only --}}
                @if(Session::get('userGroup') == 'clubleitung'
                 OR Session::get('userGroup') == 'admin')
                    <li><a href="{{ asset('/logs') }}">Logs</a></li>
                @endif

{{-- ICal feed links --}}

                    <li><a href="#" name="icalfeeds"><i class="fa fa-calendar" aria-hidden="true"></i> {{ trans('mainLang.icalfeeds') }}</a></li>

{{-- LANGUAGE SWITCHER / public --}}
                @foreach (Config::get('languages') as $lang => $language)
                    <li class="languageSwitcher"><a href="{{ route('lang.switch', $lang) }}" data-language="{{$lang}}"><i class="fa fa-globe" aria-hidden="true"></i></i> {{$language}}</a></li>
                @endforeach

                </ul>
            </li>
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
                        {!! Form::open( array('url'    => 'login', 
                                              'method' => 'POST', 
                                              'class'  => 'form-horizontal navbar-right') ) !!}
                            
                            <div class="navbar-form form-horizontal">
                                {!! Form::text( 'username', 
                                                Input::old( 'username' ),  
                                                array('placeholder'  => Lang::get('mainLang.clubNumber'),
                                                      'class'        => 'form-control',
                                                      'autocomplete' => 'on',
                                                      'style'        => 'cursor: auto') ) !!} 
                                
                                <br class="visible-xs">

                                {!! Form::password( 'password', 
                                                   ['placeholder'  => Lang::get('mainLang.password' ),
                                                    'class'        => 'form-control',
                                                    'autocomplete' => 'off',
                                                    'style'        => 'cursor: auto'] ) !!} 
                                
                                <br class="visible-xs">

                                {!! Form::submit( Lang::get('mainLang.logIn'), 
                                                  array('class' => ' btn btn-primary btn-sm') ) !!} 
                                
                                <br class="visible-xs">
                            </div>
                        {!! Form::close() !!}
                    </li>

                @endif
            </div>
            <span class="col-xs-1 visible-xs">&nbsp;</span>
          </ul>

        </div> 
    </div>
</nav>