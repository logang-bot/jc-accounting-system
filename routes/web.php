<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalancesController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\ComprobantesController;
use App\Http\Controllers\EstadoResultadosController;
use App\Http\Controllers\LibroDiarioController;
use App\Http\Controllers\LibroMayorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Rutas para autenticacion
// Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
// Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    // Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

// --- User management (admins only) ---
Route::middleware(['auth', 'role:Administrator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/users/crear', [UserController::class, 'create'])->name('show.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users', [UserController::class, 'index'])->name('show.users.index');
    });

Route::middleware('auth')->controller(EmpresasController::class)->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Rutas para empresas
    Route::prefix('/empresas')->group(function () {

        // Rutas para vistas
        Route::get('/home/{id}', 'home')->name('show.empresas.home');
        Route::get('/crear', 'create')->name('show.empresas.create');
        Route::get('/{id}', 'show')->name('show.empresas.detail');
        Route::get('/edit/{id}', 'edit')->name('show.empresas.edit');

        // Rutas de funcionalidades
        Route::post('/', 'store')->name('empresas.store');
        Route::put('/{id}', 'update')->name('empresas.update');
        Route::delete('/{id}', 'destroy')->name('empresas.destroy');

        Route::middleware('role:Administrator')->group(function () {
            Route::post('/{id}', 'archive')->name('empresas.archive');
        });
        Route::post('/exit', 'exit')->name('empresas.exit');
    });
});

Route::middleware('auth')->controller(CuentaController::class)->group(function () {

    Route::prefix('/cuentas')->group(function () {

        // Rutas para vistas
        Route::get('/home', 'home')->name('show.cuentas.home');
        Route::get('/crear', 'create')->name('show.cuentas.create');
        // Route::get('/{id}', 'show')->name('show.cuentas.detail');
        Route::get('/edit/{id}', 'edit')->name('show.cuentas.edit');

        // Rutas de funcionalidades
        Route::get('/pdf', 'exportPdf')->name('cuentas.pdf');
        Route::post('/', 'store')->name('cuentas.store');
        Route::put('/{id}', 'update')->name('cuentas.update');
        Route::delete('/delete/{id}', 'destroy')->name('cuentas.destroy');
    });
});

Route::get('/cuentas/reporte', [CuentaController::class, 'reporte'])->name('cuentas.reporte');

// Comprobantes////////////////////////////////////////////////
Route::middleware('auth')->controller(ComprobantesController::class)->group(function () {
    Route::prefix('/comprobantes')->group(function () {

        // Rutas para vistas
        Route::get('/home', 'home')->name('show.comprobantes.home');
        Route::get('/crear', 'create')->name('show.comprobantes.create');
        Route::get('/{id}', 'show')->name('show.comprobantes.detail');
        Route::get('/edit/{id}', 'edit')->name('show.comprobantes.edit');

        // Rutas de funcionalidades
        Route::get('/{id}/pdf', 'generatePDF')->name('comprobantes.pdf');
        Route::post('/', 'store')->name('comprobantes.store');
        Route::put('/{id}', 'update')->name('comprobantes.update');
        Route::delete('/delete/{id}', 'destroy')->name('comprobantes.destroy');
    });
});

Route::middleware('auth')->controller(LibroDiarioController::class)->group(function () {
    Route::get('/libro-diario', 'index')->name('libro-diario.index');
    Route::get('/libro-diario/pdf', 'exportPdf')->name('libro-diario.pdf');
});


Route::middleware('auth')->controller(LibroMayorController::class)->group(function () {
    Route::get('/libro-mayor', 'index')->name('show.libro-mayor.index');
    Route::get('/libro-mayor/varias', 'varias')->name('show.libro-mayor.varias');

    // Generar reporte (PDF, XLS)
    Route::get('/libro-mayor/pdf', 'generarPDF')->name('libro-mayor.pdf');
    Route::get('/libro-mayor/varias/reporte', [LibroMayorController::class, 'variasReporte'])
        ->name('libroMayor.varias.reporte');
});

Route::middleware('auth')->controller(BalancesController::class)->group(function () {
    Route::get('/balances/general', 'balanceGeneral')->name('balances.general');

    Route::get('/balances/pdf', 'exportPdf')->name('balances.pdf');
});

Route::middleware('auth')->controller(EstadoResultadosController::class)->group(function () {
    Route::get('/reportes/estado-resultados', 'index')->name('estado-resultados.index');

    Route::get('/reportes/estado-resultados/pdf', [EstadoResultadosController::class, 'exportarPDF'])
        ->name('estado_resultados.pdf');
});
