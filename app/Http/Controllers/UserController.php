<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Session\Session;


class UserController
{
    // Show the login form
    public function showLoginForm()
    {
        return view('login');
    }
    // Handle login
    public function login(Request $request)
    {


        $credentials = $request->only('username', 'password');
    
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Store the session ID in the sessions table
            $session = new Session();
            $session->user_id = $user->id;
            $session->session_id = session()->getId(); // Get the session ID
            $session->save();
            return redirect()->intended('mails');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
