<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\JobSpecController;
use App\Http\Controllers\ExtractorController;


Route::middleware(['auth'])->group(function () {
    
    Route::get('/', [MailController::class, 'showAllMails'])->name('mails');
    Route::get('/mails', [MailController::class, 'showAllMails'])->name('mails');
    Route::get('/trash', [MailController::class, 'showAllTrashedMails'])->name('trash');
    Route::post('mail/trash/{id}', [MailController::class, 'trashMail'])->name('mail.trash');
    Route::post('mail/recover/{id}', [MailController::class, 'recoverMail'])->name('mail.recover');

    Route::get('/', [MailController::class, 'refreshMails'])->name('refresh-mails');
    
    
    Route::get('/mailview/{id}', [MailController::class, 'showMail'])->name('mailview');
    

    Route::post('/jobdata/{id}' ,[JobSpecController::class, 'JobData'])->name('jobdata');
    Route::get('/get-workflows', [JobSpecController::class, 'Workflows'])->name('Workflows');



    Route::post('/extractApi/{id}', [ExtractorController::class, 'extractApi'])->name('extractApi');    
    
    

    
    

});
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');


Route::get('/upload', function () {
    return view('upload_form');
})->name('upload.form');

Route::post('/upload', [FileUploadController::class, 'uploadMethod'])->name('upload.file');