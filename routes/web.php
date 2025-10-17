<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\Admin\AdminController as AdminDashboardController;
use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\ExportCitasController;
use App\Http\Controllers\Paciente\AdminController as PacienteDashboardController;
use App\Http\Controllers\Doctor\AdminController as DoctorDashboardController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\Api\TarifaController;
use App\Http\Controllers\Doctor\RecetaController;
use App\Http\Controllers\EmailCitaActionController;
use App\Http\Controllers\Api\DoctorSlotController;
use App\Models\User;

// === API tarifas
Route::get('/api/tarifa/doctor/{id}', [TarifaController::class, 'precioDoctor'])
    ->whereNumber('id')
    ->name('api.tarifa.doctor.show');

// === API slots disponibles
Route::get('/api/doctor/{doctor}/fecha/{fecha}/slots', DoctorSlotController::class)
    ->whereNumber('doctor')
    ->where('fecha','\d{4}-\d{2}-\d{2}')
    ->name('api.doctor.slots');

// === Página principal
Route::get('/', fn() => view('welcome'));

// === Autenticación
Auth::routes(['register' => false]);

// === Doctores por especialidad
Route::get('/especialidades/{especialidad}/doctores', [AdminDashboardController::class, 'doctoresPorEspecialidad'])
    ->name('especialidades.doctores');

// === Home con redirección según rol
Route::get('/home', function () {
    if (!Auth::check()) return redirect('/');
    $u = Auth::user();
    if ($u->hasRole('administrador')) return redirect()->route('admin.dashboard');
    if ($u->hasRole('paciente')) return redirect()->route('paciente.dashboard');
    if ($u->hasRole('doctor')) return redirect()->route('doctor.dashboard');
    return redirect('/');
})->name('home');

// === Contacto
Route::get('/contacto', [ContactoController::class, 'mostrarFormulario'])->name('contacto.form');
Route::post('/contacto', [ContactoController::class, 'enviarFormulario'])->name('contacto.enviar');

// === Rutas firmadas por email
Route::middleware('signed')->get('/email/cita/{cita}/{rol}/{accion}', EmailCitaActionController::class)
    ->where('rol', '^(paciente|doctor)$')
    ->where('accion', '^(aceptar|cancelar)$')
    ->name('email.cita.action');

// ============================================================
// ADMINISTRADOR
// ============================================================
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/resumen', [AdminDashboardController::class, 'resumenGlobal'])->name('admin.dashboard.resumen');
    
    // Perfil
    Route::get('/perfil', [AdminDashboardController::class, 'editarPerfil'])->name('admin.perfil.edit');
    Route::post('/perfil', [AdminDashboardController::class, 'actualizarPerfil'])->name('admin.perfil.update');
    
    // Usuarios (módulo completo)
    Route::get('/usuarios', [AdminDashboardController::class, 'usuarios'])->name('admin.usuarios.index');
    Route::get('/usuarios/crear', [AdminDashboardController::class, 'usuariosCreate'])->name('admin.usuarios.create');
    Route::post('/usuarios', [AdminDashboardController::class, 'usuariosStore'])->name('admin.usuarios.store');
    Route::get('/usuarios/{user}', [AdminDashboardController::class, 'usuariosShow'])->name('admin.usuarios.show');
    Route::get('/usuarios/{user}/editar', [AdminDashboardController::class, 'usuariosEdit'])->name('admin.usuarios.edit');
    Route::put('/usuarios/{user}', [AdminDashboardController::class, 'usuariosUpdate'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{user}', [AdminDashboardController::class, 'usuariosDestroy'])->name('admin.usuarios.destroy');

    // Exportes
    Route::get('/usuarios/export/excel', [AdminDashboardController::class, 'usuariosExportExcel'])->name('admin.usuarios.export.excel');
    Route::get('/usuarios/export/pdf',   [AdminDashboardController::class, 'usuariosExportPdf'])->name('admin.usuarios.export.pdf');

    // === Acciones de estado de cuenta ===
    Route::patch('/usuarios/{user}/block', function(User $user){
        $user->update([
            'status' => 'blocked',
            'suspended_until' => null,
            'deactivation_reason' => request('reason'),
        ]);
        return back()->with('success','Usuario bloqueado');
    })->name('admin.usuarios.block');

    Route::patch('/usuarios/{user}/suspend', function(User $user){
        $until = request('until'); // formato YYYY-MM-DD HH:MM
        $user->update([
            'status' => 'active',
            'suspended_until' => $until,
            'deactivation_reason' => request('reason'),
        ]);
        return back()->with('success','Usuario suspendido temporalmente');
    })->name('admin.usuarios.suspend');

    Route::patch('/usuarios/{user}/activate', function(User $user){
        $user->update([
            'status' => 'active',
            'suspended_until' => null,
            'deactivation_reason' => null,
        ]);
        return back()->with('success','Usuario reactivado');
    })->name('admin.usuarios.activate');

    Route::patch('/usuarios/{user}/deactivate', function(User $user){
        $user->update([
            'status' => 'inactive',
            'suspended_until' => null,
            'deactivation_reason' => request('reason'),
        ]);
        return back()->with('success','Usuario marcado como inactivo');
    })->name('admin.usuarios.deactivate');
    
    // Doctores
    Route::get('/doctores/crear', [AdminDashboardController::class, 'crearDoctor'])->name('admin.doctores.crear');
    Route::post('/doctores', [AdminDashboardController::class, 'storeDoctor'])->name('admin.doctores.store');
    
    // Pacientes
    Route::get('/pacientes/crear', [AdminDashboardController::class, 'crearPaciente'])->name('admin.pacientes.crear');
    Route::post('/pacientes', [AdminDashboardController::class, 'storePaciente'])->name('admin.pacientes.store');
    
    // Exportar citas
    Route::get('/citas/export', [ExportCitasController::class, 'exportarCitas'])->name('admin.citas.export');

    // HORARIOS
    Route::get('/horarios', [HorarioController::class, 'index'])->name('admin.horarios.index');
    Route::get('/horarios/crear', [HorarioController::class, 'create'])->name('admin.horarios.create');
    Route::post('/horarios', [HorarioController::class, 'store'])->name('admin.horarios.store');
    Route::get('/horarios/{horario}/editar', [HorarioController::class, 'edit'])->name('admin.horarios.edit');
    Route::put('/horarios/{horario}', [HorarioController::class, 'update'])->name('admin.horarios.update');
    Route::delete('/horarios/{horario}', [HorarioController::class, 'destroy'])->name('admin.horarios.destroy');
});

// ============================================================
// PACIENTE
// ============================================================
Route::middleware(['auth', 'role:paciente'])->prefix('paciente')->group(function () {
    Route::get('/dashboard', [PacienteDashboardController::class, 'dashboard'])->name('paciente.dashboard');
    Route::get('/perfil', [PacienteDashboardController::class, 'editarPerfil'])->name('paciente.perfil.edit');
    Route::post('/perfil', [PacienteDashboardController::class, 'actualizarPerfil'])->name('paciente.perfil.update');
    Route::get('/citas', [CitaController::class, 'index'])->name('paciente.citas');
    Route::get('/crear-cita', [CitaController::class, 'create'])->name('paciente.crear-cita');
    Route::post('/crear-cita', [CitaController::class, 'store'])->name('paciente.crear-cita.store');
    Route::post('/citas/{id}/cancelar', [CitaController::class, 'cancelar'])->name('paciente.citas.cancelar');
    Route::get('/editar-cita/{id}', [CitaController::class, 'edit'])->name('paciente.editar-cita');
    Route::put('/editar-cita/{id}', [CitaController::class, 'actualizar'])->name('paciente.editar-cita.update');
    Route::view('/historial', 'paciente.historial')->name('paciente.historial');
    Route::view('/mensajes', 'paciente.mensajes')->name('paciente.mensajes');
});

// ============================================================
// DOCTOR
// ============================================================
Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->group(function () {
    Route::get('/dashboard', [DoctorDashboardController::class, 'dashboard'])->name('doctor.dashboard');
    Route::get('/dashboard/data', [DoctorDashboardController::class, 'dashboardData'])->name('doctor.dashboard.data');
    Route::get('/perfil', [DoctorDashboardController::class, 'editarPerfil'])->name('doctor.perfil.edit');
    Route::post('/perfil', [DoctorDashboardController::class, 'actualizarPerfil'])->name('doctor.perfil.update');
    Route::get('/citas', [CitaController::class, 'indexDoctor'])->name('doctor.citas');
    Route::post('/citas/{id}/aceptar', [CitaController::class, 'aceptar'])->name('doctor.citas.aceptar');
    Route::post('/citas/{id}/rechazar', [CitaController::class, 'rechazar'])->name('doctor.citas.rechazar');
    Route::post('/citas/{id}/realizar', [CitaController::class, 'realizar'])->name('doctor.citas.realizar');
    Route::get('/recetas', [RecetaController::class, 'index'])->name('doctor.recetas.index');
    Route::get('/recetas/crear/{cita}', [RecetaController::class, 'create'])->name('doctor.recetas.create');
    Route::post('/recetas', [RecetaController::class, 'store'])->name('doctor.recetas.store');
    Route::get('/recetas/editar/{cita}', [RecetaController::class, 'edit'])->name('doctor.recetas.edit');
    Route::post('/recetas/actualizar', [RecetaController::class, 'update'])->name('doctor.recetas.update');
    Route::post('/recetas/reenviar/{cita}', [RecetaController::class, 'resend'])->name('doctor.recetas.resend');
    Route::get('/recetas/descargar/{cita}', [RecetaController::class, 'download'])->name('doctor.recetas.download');
});

// ============================================================
// LOGOUT
// ============================================================
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

// ============================================================
// LOGIN (redirige al modal, conservando mensajes de error)
// ============================================================
Route::get('/login', function () {
    session()->reflash();
    return redirect('/?login=1');
})->name('login');
