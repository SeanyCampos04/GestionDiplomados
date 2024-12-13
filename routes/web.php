<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DiplomadosController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistroDiplomadoController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\SolicitudDocenteController;
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\SolicitudInstructorController;
use App\Http\Controllers\UsuarioController;
use App\Models\Diplomado;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí puedes registrar las rutas web para tu aplicación. Estas rutas
| son cargadas por el RouteServiceProvider y todas ellas serán asignadas
| al grupo de middleware "web". ¡Haz algo grandioso!
|
*/

// Ruta principal que redirige a login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard, disponible solo para usuarios autenticados y verificados
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'tipo:1'])->group(function () {
    // Solicitudes docente
    Route::post('/solicitar-participante/{diplomado}', [SolicitudesController::class, 'solicitar_Docente_Oferta'])->name('solicitar_docente_oferta.store');
    Route::get('/diplomados/en-curso/docente', [DiplomadosController::class, 'curso_docente'])->name('curso_docente');
    Route::get('/diplomados/terminados/docente', [DiplomadosController::class, 'terminado_docente'])->name('terminado_docente');
    Route::get('/diplomado/detalles/participante/{id}', [DiplomadosController::class, 'detalles_participante'])->name('diplomados.detalle.participante');
});

Route::middleware(['auth', 'role_or_tipo:Instructor,1'])->group(function () {
    Route::get('/oferta', [DiplomadosController::class, 'mostrarOferta'])->name('oferta');
    // Solicitudes de participante e Instructor
    Route::get('/solicitudes', [SolicitudesController::class, 'solicitudes'])->name('solicitudes.index');
});

Route::middleware(['auth', 'role:admin,CAD'])->group(function () {
    // Usuarios
    Route::get('/registrarUsuario', [RegisteredUserController::class, 'usuario_create'])->name('registrar_usuario');
    Route::post('/registrarUsuario', [RegisteredUserController::class, 'usuario_store'])->name('registrar_usuario.store');
    Route::get('usuario/editar/{id}', [UsuarioController::class, 'edit'])->name('usuario.edit');
    Route::put('/usuario/{usuario}', [UsuarioController::class, 'update'])->name('user.update');
    Route::put('usuario/{id}/Activar', [UsuarioController::class, 'activar'])->name('usuario.activar');
    Route::put('usuario/{id}/desactivar', [UsuarioController::class, 'desactivar'])->name('usuario.desactivar');

    // Diplomados
    Route::get('/registrodiplomado', [DiplomadosController::class, 'create'])->name('registrodiplomado');
    Route::post('/registrodiplomado', [DiplomadosController::class, 'store'])->name('registrodiplomado.store');
    Route::delete('/registrodiplomado/{id}', [DiplomadosController::class, 'destroy'])->name('registrodiplomado.destroy');
    Route::get('registrodiplomado/{id}/edit', [DiplomadosController::class, 'edit'])->name('registrodiplomado.edit');
    Route::put('registrodiplomado/{id}', [DiplomadosController::class, 'update'])->name('registrodiplomado.update');

    // modulos
    Route::get('/registrar_modulos/{id}', [ModuloController::class, 'create'])->name('registrar_modulos');
    Route::post('/registrar_modulos', [ModuloController::class, 'store'])->name('registrar_modulos.store');
    Route::get('modulos/{id}/edit', [ModuloController::class, 'edit'])->name('modulos.edit');
    Route::put('modulos/{id}', [ModuloController::class, 'update'])->name('modulos.update');

    // solicitudes

    Route::put('solicitudDocente/aceptar/{id}', [SolicitudesController::class, 'aceptar_docente'])->name('solicitudes_aceptar_docente');
    Route::put('solicituDocente/negar/{id}', [SolicitudesController::class, 'negar_docente'])->name('solicitudes_negar_docente');

    Route::put('solicitudInstructor/aceptar/{id}', [SolicitudesController::class, 'aceptar_instructor'])->name('solicitudes_aceptar_instructor');
    Route::put('solicitudInstructor/negar/{id}', [SolicitudesController::class, 'negar_instructor'])->name('solicitudes_negar_instructor');

    Route::get('/solicitudes/{id}', [SolicitudesController::class, 'index'])->name('solicitudes_diplomado.index');
});

Route::middleware(['auth', 'role:admin,CAD,Jefe Departamento,Subdirector Academico'])->group(function () {
    // Usuarios
    Route::get('usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/datos/{id}', [UsuarioController::class, 'show'])->name('usuario_datos.index');
    // Departamentos
    Route::resource('departamentos', DepartamentoController::class);

    // Diplomados
    Route::get('/diplomadosregistrados', [DiplomadosController::class, 'index'])->name('diplomados.index');
    Route::get('/diplomado/detalles/{id}', [DiplomadosController::class, 'detalles'])->name('diplomados.detalle');
});

Route::middleware(['auth', 'role:Instructor'])->group(function () {
    Route::get('/diplomado/en-curso/instructor', [DiplomadosController::class, 'curso_instructor'])->name('curso_instructor');
    Route::get('/diplomado/terminados/instructor', [DiplomadosController::class, 'terminado_instructor'])->name('terminado_instructor');
    Route::get('/diplomado/en-curso/instructor/detalle/{id}', [DiplomadosController::class, 'detalles_instructor'])->name('diplomados.detalle.instructor');
    Route::get('/diplomado/en-curso/instructor/detalle/modulo/{modulo}', [ModuloController::class, 'detalles_modulo'])->name('diplomados.detalle.modulo.participantes');
    Route::post('/Calificar/participante', [ModuloController::class, 'actualizar_calificacion_participante'])->name('actualizar.calificacion.modulo.participante');

    // Solicitudes instructor
    Route::post('/solicitar-instructor/{diplomado}', [SolicitudesController::class, 'solicitar_instructor_Oferta'])->name('solicitar_instructor_oferta.store');
});


//Vista docente
Route::get('/en_curso', function () {
    return view('diplomados.participante.en_curso');
})->name('en_curso');

Route::get('/historial', function () {
    return view('historial');
})->name('historial');

Route::get('/progreso', function () {
    return view('progreso');
})->name('progreso');

Route::get('/calificar', function () {
    return view('calificar');
})->name('calificar');



/*Route::get('/registro_usuario', function () {
    return view('registro_usuario');
})->name('registro_usuario');*/

// Rutas de autenticación (login, registro, etc.)
require __DIR__ . '/auth.php';
