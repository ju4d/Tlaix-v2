<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller {
    public function showLogin(){ return view('auth.login'); }

    public function login(Request $r){
        $user = User::where('email',$r->email)->first();
        if(!$user) return back()->withErrors(['email'=>'Usuario no encontrado']);
        if(!Hash::check($r->password,$user->password)) return back()->withErrors(['password'=>'Contraseña incorrecta']);
        // store session simple
        session(['user_id'=>$user->id,'user_role'=>$user->role,'user_name'=>$user->name]);
        return redirect('/dashboard');
    }

    public function logout(){ session()->flush(); return redirect('/login'); }
}
