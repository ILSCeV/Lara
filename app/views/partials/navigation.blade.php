   {{-- NAVIGATION --}}
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
                <li><a href="{{ Request::getBasePath() }}/calendar/month">Monatsansicht</a></li>
                @if(Session::has('userId'))
                <li><a href="{{ Request::getBasePath() }}/calendar/week">Wochenansicht</a></li>
                @endif
                <li><a href="{{ Request::getBasePath() }}/schedule">Dienstpläne</a></li>
                @if(Session::has('userId'))
                <li><a href="{{ Request::getBasePath() }}/task">Aufgaben</a></li>    
                <li><a href="{{ Request::getBasePath() }}/statistics">Dienstestatistik</a></li>
                @endif
            </ul> 

            <ul class="nav navbar-nav navbar-right">    

                {{-- LANGUAGE SWITCHER --}}
                {{-- <li><a href="#"><img src="{{ Request::getBasePath() }}/en.png" alt="EN"></a></li> --}}
                {{-- <li><a href="#"><img src="{{ Request::getBasePath() }}/de.png" alt="DE"></a></li> --}}
            
            
                {{-- LOGIN FORM --}}
                @if(Session::has('userId'))
                <li style="padding-top: 5px;">
                    {{ Form::open(array('url' => 'logout', 
                                        'method' => 'POST', 
                                        'class'=>'form-horizontal', 
                                        'role'=>'form')) }}
                        <div class="navbar-form">
                            Eingeloggt als <strong>{{ Session::get('userName') }} ({{ Session::get('userGroup') }})</strong>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            {{ Form::submit('Abmelden', array('class'=>'btn btn-default btn-sm')) }}
                        </div>
                    {{ Form::close() }}

                @else
                <li>
                    {{ Form::open(array('url' => 'login', 
                                        'method' => 'POST', 
                                        'class'=>'form-horizontal navbar-right', 
                                        'role'=>'form')) }}

                        <div class="navbar-form form-horizontal">
                            {{ Form::text('username', Input::old('username'),  array('placeholder'=>'Clubnummer', 
                                                                                     'class'=>'form-control',
                                                                                     'autocomplete'=>'on',
                                                                                     'style'=>'cursor: auto')) }}

                            {{ Form::password('password', array('placeholder'=>'Passwort',
                                                                'class'=>'form-control',
                                                                'autocomplete'=>'off', 
                                                                'style'=>'cursor: auto')) }}

                            {{ Form::submit('Anmelden', array('class'=>' btn btn-primary btn-sm')) }}
                        </div>

                    {{ Form::close() }}

                @endif
                </li>
            </div> 
        </div>
    </nav>


