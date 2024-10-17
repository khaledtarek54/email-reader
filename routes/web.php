<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
