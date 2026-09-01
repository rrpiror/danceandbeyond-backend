<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/terms', 'legal.terms')->name('terms');
Route::view('/support', 'legal.support')->name('support');
Route::redirect('/privacy-policy', '/privacy');
Route::redirect('/terms-and-policies', '/terms');
