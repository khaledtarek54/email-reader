<?php

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::middleware(['auth'])->group(function () {
    
    Route::get('/', [MailController::class, 'showAllMails'])->name('mails');
    Route::get('/mails', [MailController::class, 'showAllMails'])->name('mails');
    
    Route::get('/mailview', function () {
        return view('mailview');
    })->name('mailview');
    Route::get('/jobdata', function () {
        return view('jobdata');
    });
    Route::get('/jobplan', function () {
        return view('jobplan');
    });
    Route::get('/trash', function () {
        return view('trash');
    });
    
    

});
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

