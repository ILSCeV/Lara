<?php

namespace Lara\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Lara\Http\Middleware\RejectGuests;

use Lara\Http\Requests\Request;
use Lara\Shift;
use Lara\User;
use Lara\Utilities;
use LaravelWebauthn\Models\WebauthnKey;
use LaravelWebauthn\Services\Webauthn;
use PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException;
use PragmaRX\Google2FA\Exceptions\InvalidCharactersException;
use PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException;
use PragmaRX\Google2FA\Google2FA;
use Webauthn\PublicKeyCredentialRpEntity;

class UserPersonalPageController extends Controller
{
    /**
     * PublicKey Creation session name.
     *
     * @var string
     */
    private const SESSION_PUBLICKEY_CREATION = 'webauthn.publicKeyCreation';

    public function __construct()
    {
        $this->middleware(RejectGuests::class);
    }

    public function showPersonalPage()
    {
        $user = \Auth::user();

        $shifts = Shift::query()->where('person_id', '=', $user->person->id)
            ->with("schedule", "schedule.event.section", "schedule.event", "type")
            ->whereHas("schedule.event", function ($query) {
                $query->where('evnt_date_start', '>=', new \DateTime());
            })
            ->get()->sortBy('schedule.event.evnt_date_start');
        $webauthnKeys = WebauthnKey::query()->where('user_id','=', $user->id);
        /** @var Google2FA $google2fa */
        $google2fa = app('pragmarx.google2fa');
        $secret = $google2fa->generateSecretKey();
        $qrImage = $google2fa->getQRCodeInline(config('app.name'), \Auth::user()->email, $secret);
        $publicKey = Session::get(self::SESSION_PUBLICKEY_CREATION, app(Webauthn::class)->getRegisterData($user));
        Session::put(self::SESSION_PUBLICKEY_CREATION, $publicKey);

        return View::make('userpersonalpage.index', compact('user', 'shifts', 'secret', 'qrImage', 'publicKey','webauthnKeys'));
    }

    public function updatePerson()
    {
        $request = request();
        $user = \Auth::user();
        $isNamePrivate = $request->input("is_name_private") == 'null' ? null : $request->input("is_name_private") == 'true';
        $user->is_name_private = $isNamePrivate;
        $user->save();
        // Return to the the section management page
        Session::put('message', trans('mainLang.changesSaved'));
        Session::put('msgType', 'success');

        return \Redirect::back();
    }

    public function registerGoogleAuth()
    {
        /** @var Google2FA $google2fa */
        $google2fa = app('pragmarx.google2fa');
        $request = request();
        $secret = $request->input("secret");
        $currentCode = $request->input('currentCode');
        if (!$this->validateGoogle2fa($google2fa, $currentCode, $secret)) {
            return Redirect::back()->withInput($request->all())->withErrors(['code' => 'invalid code']);
        }
        /** @var User $user */
        $user = \Auth::user();
        $user->setGoogle2faSecretAttribute($secret);
        $user->save();
        \Session::put('2faVeryfied', true);
        return Redirect::route('user.personalpage');
    }

    public function unregisterGoogleAuth()
    {
        /** @var User $user */
        $user = \Auth::user();
        $user->google2fa_secret = '';
        $user->save();
        return Redirect::back();
    }

    private function validateGoogle2fa(Google2FA $google2fa, $key, $secret)
    {
        try {
            return $google2fa->verify($key, $secret);
        } catch (IncompatibleWithGoogleAuthenticatorException $e) {
            return false;
        } catch (InvalidCharactersException $e) {
            return false;
        } catch (SecretKeyTooShortException $e) {
            return false;
        }
    }

    public function registerWebauthnKey()
    {
        $request = request();
        /**
         * @var Webauthn $webauthn
         * @var PublicKeyCredentialRpEntity $publicKey
         */
        $webauthn = app(Webauthn::class);
        $publicKey = Session::get(self::SESSION_PUBLICKEY_CREATION, $webauthn->getRegisterData($request->user()));
        $webauthn->forceAuthenticate();
        try {
            if ($webauthn->canRegister($request->user())) {
                $webauthn->doRegister(
                    $request->user(),
                    $publicKey,
                    $this->input($request, 'register'),
                    $this->input($request, 'name')
                );
                Utilities::success(trans('mainLang.changesSaved'));
            }
        } catch (\Exception $exception){
            logger("error registering key", [$exception]);
            Utilities::error(trans('mainLang.error'));
        }
        return Redirect::route('user.personalpage');
    }

    /**
     * Retrieve the input with a string result.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $name
     * @param string $default
     * @return string
     */
    private function input(\Illuminate\Http\Request $request, string $name, string $default = ''): string
    {
        $result = $request->input($name);

        return is_string($result) ? $result : $default;
    }
}
