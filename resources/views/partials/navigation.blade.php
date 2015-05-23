   <!-- NAVIGATION -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ Request::getBasePath() }}/"><img src="{{ Request::getBasePath() }}/bc-logo.png" alt="bc-Club"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ Request::getBasePath() }}/calendar/month">Monat</a></li>
                @if(Session::has('userId'))
                <li><a href="{{ Request::getBasePath() }}/calendar/week">Woche</a></li>
                @endif
                <li><a href="{{ Request::getBasePath() }}/schedule">Liste</a></li>
                @if(Session::has('userId'))
                <li><a href="{{ Request::getBasePath() }}/task">Aufgaben</a></li>    
                <!-- <li><a href="{{ Request::getBasePath() }}/statistics">Statistik</a></li> -->
                @endif
                @if(Session::has('userGroup')
                AND (Session::get('userGroup') == 'marketing'
                OR Session::get('userGroup') == 'clubleitung'))
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-cog"></i>&nbsp;&nbsp;<span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="{{ Request::getBasePath() }}/management/places">Orte verwalten</a></li>
                    <li><a href="{{ Request::getBasePath() }}/management/jobtypes">Diensttypen verwalten</a></li>
                    <li><a href="{{ Request::getBasePath() }}/management/templates">Vorlagen verwalten</a></li>
                  </ul>
                </li>
                @endif
            </ul> 

            <ul class="nav navbar-nav navbar-right">    

                <!-- LANGUAGE SWITCHER -->
                <!-- <li><a href="#"><img src="{{ Request::getBasePath() }}/en.png" alt="EN"></a></li> -->
                <!-- <li><a href="#"><img src="{{ Request::getBasePath() }}/de.png" alt="DE"></a></li> -->
            
                <li style="padding-top:17px">
                    @include('partials.create-btn')
                </li>          
                <!-- LOGIN FORM -->
                @if(Session::has('userId'))
                <li style="padding-top: 5px;">
                    {!! Form::open(array('url' => 'logout', 
                                        'method' => 'POST', 
                                        'class'=>'form-horizontal', 
                                        'role'=>'form')) !!}
                        <center class="hidden-md">
                        <div class="navbar-form">
                            Eingeloggt als <strong>{{ Session::get('userName') }} ({{ Session::get('userGroup') }})</strong>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            {!! Form::submit('Abmelden', array('class'=>'btn btn-default btn-sm')) !!}
                        </div>
                        </center>
                    {!! Form::close() !!}

                @else
                <li>
                    {!! Form::open(array('url' => 'login', 
                                        'method' => 'POST', 
                                        'class'=>'form-horizontal navbar-right', 
                                        'role'=>'form')) !!}
                        
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

                @endif
                </li>
            </div> 
        </div>
    </nav>