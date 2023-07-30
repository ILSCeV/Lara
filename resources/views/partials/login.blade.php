{!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'd-flex']) !!}

{!! Form::text('username', Request::input('username'), [
    'placeholder' => __('mainLang.clubNumber') . ' / ' . __('auth.email'),
    'class' => 'form-control form-control-sm me-1',
    'autocomplete' => 'on',
    'style' => 'cursor: auto',
    'autofocus' => 'autofocus',
]) !!}


{!! Form::password('password', [
    'placeholder' => __('mainLang.password'),
    'class' => 'form-control form-control-sm me-1',
    'autocomplete' => 'off',
    'style' => 'cursor: auto',
]) !!}

@dev
    <select name="userGroup" class="form-select form-select-sm me-1 w-auto" id="userGroupDevelop" data-style="btn btn-sm">
        @foreach (Roles::ALL_PRIVILEGES as $privilege)
            <option value="{{ $privilege }}"> {{ ucwords($privilege) }}</option>
        @endforeach
    </select>
@enddev
{!! Form::submit(__('mainLang.logIn'), ['class' => 'btn btn-primary btn-sm']) !!}

{!! Form::close() !!}
