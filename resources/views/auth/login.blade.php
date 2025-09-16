@extends('layouts.app')
@section('title','Login')
@section('content')
<form method="POST" action="/login">
    @csrf
    <div><label>Email</label><input type="email" name="email" required></div>
    <div><label>Password</label><input type="password" name="password" required></div>
    <div><button type="submit">Login</button></div>
    @if($errors->any()) <div style="color:red">{{$errors->first()}}</div> @endif
</form>
<p>For dev: create a user via tinker: User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>Hash::make('secret'),'role'=>'admin'])</p>
@endsection
