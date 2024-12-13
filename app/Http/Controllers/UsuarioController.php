<?php

namespace App\Http\Controllers;

use App\Models\cursos_instructore;
use App\Models\cursos_participante;
use App\Models\Departamento;
use App\Models\Participante;
use App\Models\Role;
use App\Models\solicitud_docente;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::all();

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $usuario = User::find($id);
        $participante = Participante::find($id);
        $diplomados = solicitud_docente::with('diplomado')->where('participante_id', $participante->id)->get();

        return view('usuarios.show', compact('usuario', 'diplomados'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = User::find($id);
        $departamentos = Departamento::all();
        $roles = Role::all();

        return view('usuarios.edit', compact('usuario', 'departamentos', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidoP' => 'nullable|string|max:255',
            'apellidoM' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'curp' => 'nullable|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'telefono' => 'nullable|string|max:15',
            'departamento' => 'required|exists:departamentos,id',
            'tipo_usuario' => 'required|integer|in:1,2,3',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $usuario = User::findOrFail($id);

        $usuario->email = $validated['email'];
        $usuario->tipo = $validated['tipo_usuario'];

        if ($request->filled('password')) {
            $usuario->password = Hash::make($validated['password']);
        }
        $usuario->save();

        $datosGenerales = $usuario->datos_generales;
        $datosGenerales->nombre = $validated['nombre'];
        $datosGenerales->apellido_paterno = $validated['apellidoP'];
        $datosGenerales->apellido_materno = $validated['apellidoM'];
        $datosGenerales->fecha_nacimiento = $validated['fecha_nacimiento'];
        $datosGenerales->curp = $validated['curp'];
        $datosGenerales->rfc = $validated['rfc'];
        $datosGenerales->telefono = $validated['telefono'];
        $datosGenerales->departamento_id = $validated['departamento'];

        $datosGenerales->save();

        $role_instructor = Role::where('nombre', 'Instructor')->first();

        if ($request->filled('roles')) {
            $usuario->roles()->sync($validated['roles']);

            if (in_array($role_instructor->id, $validated['roles'])) {
                // Si el usuario no tiene un registro de instructor, lo crea
                if (!$usuario->instructor()->exists()) {
                    $usuario->instructor()->create([
                        'user_id' => $usuario->id
                    ]);
                }
            }
        } else {
            $usuario->roles()->detach();
        }

        return Redirect::route('usuario_datos.index', $usuario->id)->with('success', 'Usuario actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function activar($id)
    {
        $usuario = User::find($id);
        $usuario->estatus = true;
        $usuario->save();
        return redirect(route('usuarios.index'));
    }
    public function desactivar($id)
    {
        $usuario = User::find($id);
        $usuario->estatus = false;
        $usuario->save();
        return redirect(route('usuarios.index'));
    }
}
