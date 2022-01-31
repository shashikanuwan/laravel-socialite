<?php

use App\Http\Controllers\SocialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/facebook', [SocialController::class, 'facebookRedirect'])
    ->name('auth.facebook');

Route::get('auth/facebook/callback', [SocialController::class, 'loginWithFacebook']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
