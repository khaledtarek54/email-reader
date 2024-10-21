<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('mails');
});Route::get('/mailview', function () {
    return view('mailview');
});
Route::get('/mails', function () {
    return view('mails');
});
Route::get('/jobdata', function () {
    return view('jobdata');
});
Route::get('/jobplan', function () {
    return view('jobplan');
});
Route::get('/trash', function () {
    return view('trash');
});
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/dashboard', function () {
    return 'Welcome to your dashboard!';
})->name('dashboard');
