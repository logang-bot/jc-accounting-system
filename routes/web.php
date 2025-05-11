<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->controller(AuthController::class)->group(function() {
    Route::get('/register', 'showRegister')->name('show.register');
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

Route::middleware('auth')->controller(EmpresasController::class)->group(function() {
    Route::prefix('empresas')->group(function () {
        Route::get('/', 'index')->name('empresas');
        Route::get('/crear', 'create')->name('empresas.create');
        Route::post('/', 'store')->name('empresas.store');
        Route::delete('/{id}', 'destroy')->name('empresas.destroy');
    });
});

