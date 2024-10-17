<?php
namespace App\Services;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials)
    {
        // Attempt to login with email and password
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }
        return false;
    }

    public function logout()
    {
        Auth::logout();
    }
}