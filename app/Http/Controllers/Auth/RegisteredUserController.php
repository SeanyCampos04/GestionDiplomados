<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\datos_generales;
use App\Models\datos_usuario;
use App\Models\Departamento;
use App\Models\Instructore;
use App\Models\Participante;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function usuario_create(): View
    {
        $departamentos = Departamento::all();
        $roles = Role::all();
        return view('auth.register', compact('departamentos', 'roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

     public function usuario_store(Request $request): RedirectResponse
     {
         // Validar los campos adicionales junto con los existentes
         $request->validate([
             'nombre' => ['required', 'string', 'max:255'],
             'apellido_paterno' => ['nullable', 'string', 'max:255'],
             'apellido_materno' => ['nullable', 'string', 'max:255'],
             'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
             'correo_alternativo' => ['nullable', 'string', 'email', 'max:255', 'unique:' . datos_generales::class],
             'password' => ['required', 'confirmed', Rules\Password::defaults()],
             'departamento' => ['required', 'int'],
             'tipo_usuario' => ['required'],
             'roles' => ['array'],
             'roles.*' => ['int', 'exists:roles,id'],
         ]);

         // Crear el usuario
         $user = User::create([
             'email' => $request->email,
             'password' => Hash::make($request->password),
             'tipo' => $request->tipo_usuario,
         ]);

         // Crear el registro en la tabla 'datos_usuario'
        datos_generales::create([
            'user_id' => $user->id,
            'departamento_id' => $request->departamento,
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'correo_alternativo' => $request->correo_alternativo,
         ]);


         $user->user_roles()->attach($request->roles, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $instructorRoleId = Role::where('nombre', 'instructor')->first()->id;

        $roleIds = $request->roles ?? [];

        if (in_array($instructorRoleId, $roleIds)) {
            Instructore::create([
                'user_id' => $user->id,
            ]);
        }

         // Crear el participante
        Participante::create([
             'user_id' => $user->id,
         ]);

         // Enviar el evento de registrado
         event(new Registered($user));

         // Redirigir al home
         return redirect(RouteServiceProvider::HOME);
     }

}
