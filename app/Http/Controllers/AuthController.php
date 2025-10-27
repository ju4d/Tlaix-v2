<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Guardar el rol del usuario en la sesión
            $user = Auth::user();
            $request->session()->put('user_role', $user->role);
            $request->session()->put('user_name', $user->name);
            $role = strtolower(trim($user->role));
            if ($role == 'admin') {
                return redirect()->intended('dashboard');
            } elseif ($role == 'mesero') {
                return redirect()->intended(route('mesero.index'));
            } elseif ($role == 'chef' || $role == 'chip') {
                return redirect()->intended(route('cocina.index'));
            } else {
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin' // rol por defecto
        ]);

        return redirect('login')->with('status', 'Usuario registrado exitosamente!');
    }
    public function showRegister()
    {
        return view('auth.register');
    }
}
