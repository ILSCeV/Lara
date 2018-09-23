{!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'form-horizontal ml-auto']) !!}


<div class="navbar-form form-horizontal login-forms">
    {!! Form::text( 'username',
                    Input::old( 'username' ),
                    ['placeholder'  => Lang::get('mainLang.clubNumber') . " / " . trans('auth.email'),
                          'class'        => 'form-control',
                          'autocomplete' => 'on',
                          'style'        => 'cursor: auto',
                          'autofocus'=>'autofocus'])  !!}

    <br class="d-block.d-sm-none">


    {!! Form::password( 'password',
                       ['placeholder'  => Lang::get('mainLang.password' ),
                        'class'        => 'form-control',
                        'autocomplete' => 'off',
                        'style'        => 'cursor: auto'] ) !!}

    <br class="d-block.d-sm-none">

    @dev
    <select name="userGroup" id="userGroup" class="btn btn-sm">
        @foreach(Roles::ALL_PRIVILEGES as $privilege)
            <option value="{{ $privilege }}"> {{ ucwords($privilege) }}</option>
        @endforeach
    </select>
    @enddev
    {!! Form::submit( Lang::get('mainLang.logIn'),
                      array('class' => ' btn btn-primary btn-sm') ) !!}

    <br class="d-block.d-sm-none">
</div>
{!! Form::close() !!}
