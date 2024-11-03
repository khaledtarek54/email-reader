<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\JobSpecController;
use App\Http\Controllers\ExtractorController;


Route::middleware(['auth'])->group(function () {

    Route::get('/', [MailController::class, 'showAllMails'])->name('mails');
    Route::get('/', [MailController::class, 'refreshMails'])->name('refresh-mails');
    Route::get('/mails', [MailController::class, 'showAllMails'])->name('mails');
    Route::get('/trash', [MailController::class, 'showAllTrashedMails'])->name('trash');
    Route::post('mail/trash/{id}', [MailController::class, 'trashMail'])->name('mail.trash');
    Route::post('mail/recover/{id}', [MailController::class, 'recoverMail'])->name('mail.recover');
    Route::get('/mailview/{id}', [MailController::class, 'showMail'])->name('mailview');


    Route::post('/jobdata/{id}', [JobSpecController::class, 'JobData'])->name('jobdata');
    Route::get('/Workflows', [JobSpecController::class, 'Workflows'])->name('Workflows');


    Route::post('/extractApi/{id}', [ExtractorController::class, 'extractApi'])->name('extractApi');


    Route::post('/fetch-files/{id}', [JobSpecController::class, 'fetchFiles']);
    Route::get('/upload', function () {
        return view('upload_form');
    })->name('upload.form');
    Route::post('/upload', [FileUploadController::class, 'uploadMethod'])->name('upload.file');
    Route::post('/fetch-files-tp/{id}', [FileUploadController::class, 'uploadFilesFromTP'])->name('uploadFilesFromTP');


    Route::post('/autoPlan', [JobController::class, 'autoPlan'])->name('autoPlan');
    Route::post('/autoPlanEdit', [JobController::class, 'autoPlanEdit'])->name('autoPlanEdit');
    Route::post('/saveAutoPlanSpecs/{id}', [JobController::class, 'saveAutoPlanSpecs'])->name('saveAutoPlanSpecs');

    Route::post('/createJob/{id}', [JobController::class, 'createJob'])->name('createJob');
    
});

Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');



