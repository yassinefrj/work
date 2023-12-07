<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\MicrosoftController;

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->middleware('guest');

Route::get('/auth/microsoft', [MicrosoftController::class, 'redirect'])->name('microsoft.login');
Route::get('/auth/microsoft/callback', [MicrosoftController::class, 'callback'])->middleware('guest');