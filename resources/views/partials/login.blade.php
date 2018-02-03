{!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'form-horizontal navbar-right']) !!}


<div class="navbar-form form-horizontal login-forms">
    <select name="loginType" id="loginType" class="selectpicker">
        <option value="LDAP">{{trans('mainLang.clubNumber')}}</option>
        <option value="Lara">E-Mail</option>
    </select>
    {!! Form::text( 'email',
                    Input::old('email'),
                    array('placeholder'  => 'Email',
                          'class'        => 'form-control',
                          'autocomplete' => 'on',
                          'style'        => 'cursor: auto',
                          'visible'      => 'false') ) !!}

    <br class="visible-xs">
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
