<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//----------------
use App\User;
use Redirect;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //Registration get
    public function index(Request $request)
    {
        if(Auth::check()){
            return redirect('/user');
        }
        return view('home');
    }
    // Registration Post
    public function registration(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed|min:8',
            'birthday' => 'required',
            'gender' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        // validation successfull and redirect to signin
        $user = new User();
        $user->saveUser($request);
        return Redirect::to('/signin')->with('registration', 'Successful registration, please sign in.');
    }
    //signin
    public function signin(Request $request)
    {
        if(Auth::check()){
            return redirect('/user');
        }
        return view('signin');
    }
    public function signinUser(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/user');
        }
    }
}