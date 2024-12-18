<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    ///**
    // * Where to redirect users after login.
    // *
    // * @var string
    // */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {
        /*// Comprueba si existe la clave 'redirect_to' en la sesión; si no, usa HOME como predeterminado
        $redirectTo = session('redirect_to', RouteServiceProvider::HOME);

        // Limpia la URL de redirección después de usarla
        session()->forget('redirect_to');

        return $redirectTo;*/
        // Obtener el valor de redirect_to de la sesión
        $redirectTo = session('redirect_to', RouteServiceProvider::HOME);

        // Validar nombres de ruta
        if (Route::has($redirectTo)) {
            $redirectTo = route($redirectTo);
        } elseif (!str_starts_with($redirectTo, '/')) {
            // Asegurar que los paths empiecen con "/"
            $redirectTo = "/$redirectTo";
        }

        session()->forget('redirect_to');

        return $redirectTo;
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        // Guarda la URL a la que el usuario quiere acceder
        if ($request->has('redirect_to')) {
            session(['redirect_to' => $request->input('redirect_to')]);
        }
        return view('auth.login');
    }
}
