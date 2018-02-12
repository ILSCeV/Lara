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
    <select name="userGroup" id="userGroup" class="btn btn-mini">
        <option value="member"> Member </option>
        <option value="marketing"> Marketing </option>
        <option value="clubleitung"> CL </option>
        <option value="admin"> Admin </option>
    </select>
    @enddev
    {!! Form::submit( Lang::get('mainLang.logIn'),
                      array('class' => ' btn btn-primary btn-sm') ) !!}

    <br class="visible-xs">
</div>
{!! Form::close() !!}
