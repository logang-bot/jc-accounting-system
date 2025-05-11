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
Route::middleware('guest')->controller(AuthController::class)->group(function() {
    
    // Rutas para vistas
    Route::get('/register', 'showRegister')->name('show.register');
    Route::get('/login', 'showLogin')->name('show.login');

    // Rutas de funcionalidades
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

Route::middleware('auth')->controller(EmpresasController::class)->group(function() {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Rutas para empresas
    Route::prefix('/empresas')->group(function () {

        // Rutas para vistas
        Route::get('/{id}/home', 'home')->name('show.empresas.home');
        Route::get('/crear', 'create')->name('show.empresas.create');
        Route::get('/ingresar-empresa/{id}', 'show')->name('show.empresas.detail');

        // Rutas de funcionalidades
        Route::post('/', 'store')->name('empresas.store');
        Route::put('/empresa/{id}', 'update')->name('empresa.update');
        Route::delete('/{id}', 'destroy')->name('empresas.destroy');
        Route::post('/exit', 'salir')->name('empresas.salir');
    });
});



//Cuentas///////////////////////////////////////////
Route::resource('cuentas', CuentaController::class);
//Adicionar
Route::post('/cuentas', [CuentaController::class, 'store'])->name('cuentas.store');
//Editar
//Route::get('/cuentas/{id}/edit', [CuentaController::class, 'edit']);
//Actualizar
Route::put('/cuentas/{id}', [CuentaController::class, 'update'])->name('cuentas.update');
//Borrar
Route::delete('/cuentas/{id_cuenta}', [CuentaController::class, 'destroy'])->name('cuentas.destroy');
//Reporte
Route::get('/cuentas/reporte', [CuentaController::class, 'reporte'])->name('cuentas.reporte');


//Comprobantes////////////////////////////////////////////////
Route::resource('comprobantes', ComprobantesController::class);
