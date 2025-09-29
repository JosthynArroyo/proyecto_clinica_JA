<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Admin\AdminController as AdminDashboardController;
use App\Http\Controllers\ExportCitasController;
use App\Http\Controllers\Paciente\AdminController as PacienteDashboardController;
use App\Http\Controllers\Doctor\AdminController as DoctorDashboardController;
use App\Http\Controllers\ContactoController;

Route::get('/', fn() => view('welcome'));

Auth::routes(['register' => false]);

Route::get('/especialidades/{especialidad}/doctores', [AdminDashboardController::class, 'doctoresPorEspecialidad'])
    ->name('especialidades.doctores');

Route::get('/home', function () {
    if (!Auth::check()) return redirect('/');
    $u = Auth::user();
    if ($u->hasRole('administrador')) return redirect()->route('admin.dashboard');
    if ($u->hasRole('paciente')) return redirect()->route('paciente.dashboard');
    if ($u->hasRole('doctor')) return redirect()->route('doctor.dashboard');
    return redirect('/');
})->name('home');

Route::get('/contacto', [ContactoController::class, 'mostrarFormulario'])->name('contacto.form');
Route::post('/contacto', [ContactoController::class, 'enviarFormulario'])->name('contacto.enviar');

Route::middleware(['auth', 'role:administrador'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/resumen', [AdminDashboardController::class, 'resumenGlobal'])->name('admin.dashboard.resumen');
    Route::get('/perfil', [AdminDashboardController::class, 'editarPerfil'])->name('admin.perfil.edit');
    Route::post('/perfil', [AdminDashboardController::class, 'actualizarPerfil'])->name('admin.perfil.update');
    Route::get('/usuarios', [AdminDashboardController::class, 'usuarios'])->name('admin.usuarios.index');
    Route::put('/usuarios/{user}', [AdminDashboardController::class, 'usuariosUpdate'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{user}', [AdminDashboardController::class, 'usuariosDestroy'])->name('admin.usuarios.destroy');
    Route::get('/doctores/crear', [AdminDashboardController::class, 'crearDoctor'])->name('admin.doctores.crear');
    Route::post('/doctores', [AdminDashboardController::class, 'storeDoctor'])->name('admin.doctores.store');
    Route::get('/pacientes/crear', [AdminDashboardController::class, 'crearPaciente'])->name('admin.pacientes.crear');
    Route::post('/pacientes', [AdminDashboardController::class, 'storePaciente'])->name('admin.pacientes.store');
    Route::get('/citas/export', [ExportCitasController::class, 'exportarCitas'])->name('admin.citas.export');
});

Route::middleware(['auth', 'role:paciente'])->prefix('paciente')->group(function () {
    Route::get('/dashboard', [PacienteDashboardController::class, 'dashboard'])->name('paciente.dashboard');
    Route::get('/perfil', [PacienteDashboardController::class, 'editarPerfil'])->name('paciente.perfil.edit');
    Route::post('/perfil', [PacienteDashboardController::class, 'actualizarPerfil'])->name('paciente.perfil.update');
    Route::get('/citas', [CitaController::class, 'index'])->name('paciente.citas');
    Route::get('/crear-cita', [CitaController::class, 'create'])->name('paciente.crear-cita');
    Route::post('/crear-cita', [CitaController::class, 'store'])->name('paciente.crear-cita.store');
    Route::post('/citas/{id}/cancelar', [CitaController::class, 'cancelar'])->name('paciente.citas.cancelar');
    Route::get('/editar-cita/{id}', [CitaController::class, 'edit'])->name('paciente.editar-cita');
    Route::post('/editar-cita/{id}', [CitaController::class, 'actualizar'])->name('paciente.editar-cita.update');
    Route::view('/historial', 'paciente.historial')->name('paciente.historial');
    Route::view('/mensajes', 'paciente.mensajes')->name('paciente.mensajes');
});

Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'dashboard'])->name('doctor.dashboard');
    Route::get('/perfil', [DoctorDashboardController::class, 'editarPerfil'])->name('doctor.perfil.edit');
    Route::post('/perfil', [DoctorDashboardController::class, 'actualizarPerfil'])->name('doctor.perfil.update');
    Route::get('/citas', [CitaController::class, 'indexDoctor'])->name('doctor.citas');
    Route::post('/citas/{id}/aceptar', [CitaController::class, 'aceptar'])->name('doctor.citas.aceptar');
    Route::post('/citas/{id}/rechazar', [CitaController::class, 'rechazar'])->name('doctor.citas.rechazar');
    Route::post('/citas/{id}/realizar', [CitaController::class, 'realizar'])->name('doctor.citas.realizar');
    Route::get('/dashboard/data', [DoctorDashboardController::class, 'dashboardData'])->name('doctor.dashboard.data');
});


Route::post('/salir', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('salir');

Route::get('/salir', function (Request $request) {
    if ($request->user()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
    return redirect('/');
})->name('salir.get');


Route::get('/login', function () {
    return redirect('/?login=1');
})->name('login');