<nav class="navbar navbar-default navbar-static-top">
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
        <a class="navbar-brand" href="{{ asset('/') }}">
          <img src="{{ asset('/logo.png') }}" alt="bc-Club">
        </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <li><a href="{{ asset('/calendar/month') }}">Monat</a></li>

            <li><a href="{{ asset('/calendar/week') }}">Woche</a></li>
            
            {{-- show logs tab for club management or admins only --}}
            @if(Session::get('userGroup') == 'clubleitung' OR Session::get('userGroup') == 'admin')
              <li><a href="{{ asset('/logs') }}">Logs</a></li>
            @endif

            {{-- remove next "FALSE AND" to show button --}}
            @if   (FALSE AND Session::has('userGroup')
              AND (Session::get('userGroup') == 'marketing'
              OR   Session::get('userGroup') == 'clubleitung'
              OR   Session::get('userGroup') == 'admin'))
            <li class="dropdown">
              <a href="#" 
                 class="dropdown-toggle" 
                 data-toggle="dropdown" 
                 role="button" aria-expanded="false">
                <i class="fa fa-cog"></i>
                &nbsp;&nbsp;
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="{{ asset('/management/places') }}">Clubs verwalten</a></li>
                <li><a href="{{ asset('/management/jobtypes') }}">Diensttypen verwalten</a></li>
                <li><a href="{{ asset('/management/templates') }}">Vorlagen verwalten</a></li>
              </ul>
            </li>
            @endif
        </ul> 

        <ul class="nav navbar-nav navbar-right">      
            <span class="col-xs-1 visible-xs">&nbsp;</span>
            <div class="col-xs-10 col-sm-12 col-md-12 no-margin no-padding">
                @if(Session::has('userId'))

                    {{-- CREATE BUTTON --}}
                    <li style="padding-top:5px" class="btn-group testleft">
                        @if(Session::has('userGroup'))
                            {{-- small [+] button--}}
                            <div style="padding-top:2px" class="btn-group">
                                <a class="btn btn-primary dropdown-toggle plusSymbol" data-toggle="dropdown"
                                   href="#">
                                    <span class="test">+</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ URL::route('event.create') }}">Neue Veranstaltung/Aufgabe
                                            hinzufügen</a></li>
                                    <li><a href="{{ URL::route('survey.create') }}">Neue Umfrage hinzufügen</a></li>
                                </ul>
                            </div>
                        @endif
                    </li>

                    {{-- LOGIN FORM --}}
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
                                       title="Kandidat"></i>
                                @elseif ( Session::get('userStatus') === 'veteran' ) 
                                    <i class="fa fa-star" 
                                       style="color:gold;"
                                       data-toggle="tooltip" 
                                       data-placement="bottom" 
                                       title="Veteran"></i>
                                @elseif ( Session::get('userStatus') === 'resigned' ) 
                                    <i class="fa fa-star-o" 
                                       style="color:gold;"
                                       data-toggle="tooltip" 
                                       data-placement="bottom" 
                                       title="ex-Mitglied"></i>
                                @elseif ( Session::get('userStatus') === 'member')
                                    <i class="fa fa-circle"
                                       style="color:forestgreen;"
                                       data-toggle="tooltip" 
                                       data-placement="bottom" 
                                       title="Aktiv"></i>
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
                                <br class="visible-xs"> 
                                <br class="visible-xs">
                                {!! Form::submit('Abmelden', array('class'=>'btn btn-default btn-sm')) !!}
                            </div>
                        {!! Form::close() !!}
                    </li>

                @else

                    <li>
                        {!! Form::open(array('url' => 'login', 
                                            'method' => 'POST', 
                                            'class'=>'form-horizontal navbar-right')) !!}
                            
                            <div class="navbar-form form-horizontal">
                                {!! Form::text('username', Input::old('username'),  array('placeholder'=>'Clubnummer', 
                                                                                         'class'=>'form-control',
                                                                                         'autocomplete'=>'on',
                                                                                         'style'=>'cursor: auto')) !!}
                                <br class="visible-xs">
                                {!! Form::password('password', array('placeholder'=>'Passwort',
                                                                    'class'=>'form-control',
                                                                    'autocomplete'=>'off', 
                                                                    'style'=>'cursor: auto')) !!}
                                <br class="visible-xs">
                                {!! Form::submit('Anmelden', array('class'=>' btn btn-primary btn-sm')) !!}
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