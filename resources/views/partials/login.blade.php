{!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'form-horizontal navbar-right']) !!}


<div class="navbar-form form-horizontal login-forms">
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

    @dev
    <select name="userGroup" id="userGroup" class="btn btn-sm">
        @foreach(Roles::ALL_PRIVILEGES as $privilege)
            <option value="{{ $privilege }}"> {{ ucwords($privilege) }}</option>
        @endforeach
    </select>
    @enddev
    {!! Form::submit( Lang::get('mainLang.logIn'),
                      array('class' => ' btn btn-primary btn-sm') ) !!}

    <br class="visible-xs">
</div>
{!! Form::close() !!}
