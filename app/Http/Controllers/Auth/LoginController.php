<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
        $this->middleware('guest')->except('logout');
    }


    protected function authenticated(Request $request, $user)
    {
        
       // dd($user);
       // event(new \App\Events\UserLoggedIn($user));
        Log::channel('act')->info($user->email.' ha iniciado sesión desde la IP:'. $request->ip().' y agente: '.$request->userAgent());
      //  return redirect()->route('home',['_token' => csrf_token()]);
    }


    public function logout(Request $request)
    {
        // Puedes realizar acciones adicionales antes de cerrar sesión, si es necesario.
        Log::channel('act')->info(auth()->user()->email.' ha cerrado sesión');

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }


}
