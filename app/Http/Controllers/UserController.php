<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Session\Session;


class UserController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }
        return view('login');
    }
    public function login(Request $request)
    {
        
        $credentials = $request->only('username', 'password');
        if ($this->authService->login($credentials['username'], $credentials['password'])) {
            return redirect()->intended('mails');
        }

        return back()->withErrors([
            'error' => 'Invalid credentials',
        ])->withInput();
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
