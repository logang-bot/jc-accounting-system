<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\ComprobantesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas para autenticacion
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
Route::middleware('guest')->controller(AuthController::class)->group(function() {
    
    // Rutas para vistas

    // Rutas de funcionalidades
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

Route::middleware('auth')->controller(EmpresasController::class)->group(function() {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Rutas para empresas
    Route::prefix('/empresas')->group(function() {

        // Rutas para vistas
        Route::get('/home/{id}', 'home')->name('show.empresas.home');
        Route::get('/crear', 'create')->name('show.empresas.create');
        Route::get('/{id}', 'show')->name('show.empresas.detail');
        Route::get('/edit/{id}', 'edit')->name('show.empresas.edit');

        // Rutas de funcionalidades
        Route::post('/', 'store')->name('empresas.store');
        Route::put('/{id}', 'update')->name('empresas.update');
        Route::delete('/{id}', 'destroy')->name('empresas.destroy');
        Route::post('/exit', 'exit')->name('empresas.exit');
    });
});

Route::middleware('auth')->controller(CuentaController::class)->group(function() {

    Route::prefix('/cuentas')->group(function() {
        
        // Rutas para vistas
        Route::get('/home', 'home')->name('show.cuentas.home');
        Route::get('/crear', 'create')->name('show.cuentas.create');
        Route::get('/{id}', 'show')->name('show.cuentas.detail');
        Route::get('/edit/{id}', 'edit')->name('show.cuentas.edit');

        // Rutas de funcionalidades
        Route::post('/', 'store')->name('cuentas.store');
        Route::put('/{id}', 'update')->name('cuentas.update');
        Route::delete('/delete/{id}', 'destroy')->name('cuentas.destroy');
    });
});

Route::get('/cuentas/reporte', [CuentaController::class, 'reporte'])->name('cuentas.reporte');

//Comprobantes////////////////////////////////////////////////
Route::middleware('auth')->controller(ComprobantesController::class)->group(function() {
    Route::prefix('/comprobantes')->group(function() {
        
        // Rutas para vistas
        Route::get('/home', 'home')->name('show.comprobantes.home');
        Route::get('/crear', 'create')->name('show.comprobantes.create');
        Route::get('/{id}', 'show')->name('show.comprobantes.detail');
        Route::get('/edit/{id}', 'edit')->name('show.comprobantes.edit');

        // Rutas de funcionalidades
        Route::post('/', 'store')->name('comprobantes.store');
        Route::delete('/delete/{id}', 'destroy')->name('comprobantes.destroy');
    });
});

