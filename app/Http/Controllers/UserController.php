<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    // First part we initialize the auth service and inject it into the controller
    // We use dependency injection to inject the AuthService into the controller,
    // this way, when a new UserController is instantiated, it will automatically be loaded
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $user = true;

        if ($user) {
            return redirect()->route('dashboard')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

}
