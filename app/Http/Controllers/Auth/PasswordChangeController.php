<?php

namespace Lara\Http\Controllers\Auth;

use Lara\Http\Controllers\Controller;
use Lara\Utilities;

use Auth;
use Input;
use Hash;
use Redirect;
use Validator;
use View;

class PasswordChangeController extends Controller
{
    public function showChangePasswordForm()
    {
        return View::make('auth.passwords.change');
    }

    public function changePassword()
    {
        // Get old password and check that it matches the one stored in the DB
        $this->validator(request()->all())->validate();
        $oldPassword = Input::get('old-password');

        $user = Auth::user();

        $isPasswordCorrect = Hash::check($oldPassword, $user->password);

        if (!$isPasswordCorrect) {
            Utilities::error(trans('auth.passwordDoesNotMatch'));
            return Redirect::back();
        }

        $newPassword = Input::get('password');
        $user->password = bcrypt($newPassword);
        $user->save();

        Utilities::success(trans('auth.passwordChanged'));

        return Redirect::to('/');
    }

    public function validator($data)
    {
        return Validator::make($data, [
            'old-password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
    }
}
