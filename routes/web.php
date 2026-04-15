<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;


Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    // === ACCESO COMÚN (Todos los Agentes) ===
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/mis-fichadas', App\Http\Livewire\MisFichadas::class)->name('mis.fichadas');
    Route::get('/mis-licencias', App\Http\Livewire\MisLicencias::class)->name('mis.licencias');
    Route::get('/mis-documentos', App\Http\Livewire\MisDocumentos::class)->name('mis.documentos');
    Route::get('/mi-perfil', App\Http\Livewire\MiPerfil::class)->name('mi.perfil');


    // === GESTIÓN DE JEFES (Agentes a Cargo) ===
    Route::middleware(['role:ADMIN,RRHH,JEFE'])->group(function () {
        Route::get('/fichadas-jefe', App\Http\Livewire\FichadasJefe::class)->name('fichadas.jefe');
    });


    // === GESTIÓN GENERAL (RRHH + ADMIN) ===
    Route::middleware(['role:ADMIN,RRHH'])->group(function () {
        Route::get('/gestion-usuarios', App\Http\Livewire\GestionUsuarios::class)->name('gestion.usuarios');
        Route::get('/gestion-horarios', App\Http\Livewire\GestionHorarios::class)->name('gestion.horarios');
        Route::get('/gestionar-licencias', App\Http\Livewire\GestionarLicencias::class)->name('gestionar.licencias');
        Route::get('/gestion-tipos-licencias', App\Http\Livewire\GestionTiposLicencias::class)->name('gestion.tipos.licencias');
        Route::get('/reportes', App\Http\Livewire\Reportes::class)->name('reportes');
        Route::get('/reportes-rrhh', App\Http\Livewire\ReportesRRHH::class)->name('reportes.rrhh');
        Route::get('/facturacion', [App\Http\Controllers\FacturacionController::class, 'index'])->name('facturacion.index');
    });


    // === GESTIÓN DE NOVEDADES (Gestor Novedades + ADMIN) ===
    Route::middleware(['role:ADMIN,GESTOR_NOVEDADES'])->group(function () {
        Route::get('/gestion-novedades', App\Http\Livewire\GestionNovedades::class)->name('gestion.novedades');
    });


    // === ADMINISTRACIÓN PURA (Solo ADMIN) ===
    Route::middleware(['role:ADMIN'])->group(function () {
        Route::get('/configuracion', App\Http\Livewire\Configuracion::class)->name('configuracion');
    });
});





