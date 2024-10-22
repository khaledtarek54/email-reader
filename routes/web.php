<?php

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::middleware(['auth'])->group(function () {
    
    Route::get('/', [MailController::class, 'showAllMails'])->name('mails');
    Route::get('/mails', [MailController::class, 'showAllMails'])->name('mails');
    Route::get('/trash', [MailController::class, 'showAllTrashedMails'])->name('trash');
    Route::post('mail/trash/{id}', [MailController::class, 'trashMail'])->name('mail.trash');
    Route::post('mail/recover/{id}', [MailController::class, 'recoverMail'])->name('mail.recover');

    Route::get('/', [MailController::class, 'refreshMails'])->name('refresh-mails');
    
    
    Route::get('/mailview/{id}', [MailController::class, 'showMail'])->name('mailview');
    

    Route::get('/jobdata', function () {
        return view('jobdata');
    });
    Route::get('/jobplan', function () {
        return view('jobplan');
    });

    
    

});
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

