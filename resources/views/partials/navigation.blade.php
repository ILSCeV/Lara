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
          <img src="{{ asset('/logo.png') }}" alt="Lara">
        </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <li><a href="{{ asset('/calendar/month') }}">Monat</a></li>
            @if(Session::has('userId'))
            <li><a href="{{ asset('/calendar/week') }}">Woche</a></li>
            @endif
            <li><a href="{{ asset('/schedule') }}">Liste</a></li>
            @if(Session::has('userId'))
            <li><a href="{{ asset('/task') }}">Aufgaben</a></li>
            @endif
            @if   (Session::has('userGroup')
              AND (Session::get('userGroup') == 'marketing'
              OR   Session::get('userGroup') == 'clubleitung'))
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
            <li style="padding-top:17px">
                @include('partials.create-btn')
            </li>          
            <!-- LOGIN FORM -->
            @if(Session::has('userId'))
            <li style="padding-top: 5px;">
                {!! Form::open(array('url' => 'logout', 
                                    'method' => 'POST', 
                                    'class'=>'form-horizontal')) !!}
                    <center class="hidden-md">
                    <div class="navbar-form">
                        &nbsp;&nbsp;
                        @if     ( Session::get('userStatus') === 'candidate' ) 
                            <i class="fa fa-adjust" 
                               style="color:yellowgreen;"
                               data-toggle="tooltip" 
                               data-placement="top" 
                               title="Kandidat"></i>
                        @elseif ( Session::get('userStatus') === 'veteran' ) 
                            <i class="fa fa-star" 
                               style="color:gold;"
                               data-toggle="tooltip" 
                               data-placement="top" 
                               title="Veteran"></i>
                        @elseif ( Session::get('userStatus') === 'retired' ) 
                            <i class="fa fa-star-o" 
                               style="color:gold;"
                               data-toggle="tooltip" 
                               data-placement="top" 
                               title="ex-Mitglied"></i>
                        @elseif ( Session::get('userStatus') === 'member')
                            <i class="fa fa-circle" 
                               style="color:forestgreen;"
                               data-toggle="tooltip" 
                               data-placement="top" 
                               title="Aktiv"></i>
                        @endif
                        &nbsp;
                        <strong>
                            {{ Session::get('userName') }} 
                            ( {{ Session::get('userClub') }} 
                                    @if(Session::has('userGroup')
                                    AND (Session::get('userGroup') == 'marketing'
                                    OR Session::get('userGroup') == 'clubleitung'))
                                        {{ Session::get('userGroup') }}
                                    @endif
                                    )
                        </strong>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        {!! Form::submit('Abmelden', array('class'=>'btn btn-default btn-sm')) !!}
                    </div>
                    </center>
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

                        {!! Form::password('password', array('placeholder'=>'Passwort',
                                                            'class'=>'form-control',
                                                            'autocomplete'=>'off', 
                                                            'style'=>'cursor: auto')) !!}

                        {!! Form::submit('Anmelden', array('class'=>' btn btn-primary btn-sm')) !!}
                    
                    </div>
                {!! Form::close() !!}
            </li>
            @endif
          </ul>
        </div> 
    </div>
</nav>