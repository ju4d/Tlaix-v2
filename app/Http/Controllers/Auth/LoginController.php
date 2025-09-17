<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Guardar user_id en la sesión para el middleware personalizado
            session(['user_id' => Auth::id()]);
            return redirect()->intended('/home');
        } else {
            // Falló la autenticación
            return back()->withErrors(['email' => 'Credenciales incorrectas']);
        }
    }
}
