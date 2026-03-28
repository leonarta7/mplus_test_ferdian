<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

Route::get('/login', [MainController::class, 'login'])->name('login');
Route::post('/login', [MainController::class, 'submitLogin'])->name('login.submit');
Route::post('/login_third_party', [MainController::class, 'thirdPartyLogin'])->name('login.third_party');

Route::get('/register', [MainController::class, 'register'])->name('register');
Route::post('/register', [MainController::class, 'submitRegister'])->name('register.submit');

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('home');
    Route::post('/logout', [MainController::class, 'logout'])->name('logout');
});
