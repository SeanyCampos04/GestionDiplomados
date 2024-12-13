<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Usuario') }}
        </h2>
    </x-slot>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('registrar_usuario.store') }}">
                @csrf

                <!-- Nombre -->
                <div>
                    <x-input-label for="nombre" :value="__('Nombre')" />
                    <x-text-input id="nombre" class="block mt-1 w-full" type="text" name="nombre"  required autofocus/>
                    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                </div>

                <!-- Apellido Paterno -->
                <div class="mt-4">
                    <x-input-label for="apellido_paterno" :value="__('Apellido Paterno')" />
                    <x-text-input id="apellido_paterno" class="block mt-1 w-full" type="text" name="apellido_paterno"/>
                    <x-input-error :messages="$errors->get('apellido_paterno')" class="mt-2" />
                </div>

                <!-- Apellido Materno -->
                <div class="mt-4">
                    <x-input-label for="apellido_materno" :value="__('Apellido Materno')" />
                    <x-text-input id="apellido_materno" class="block mt-1 w-full" type="text" name="apellido_materno" />
                    <x-input-error :messages="$errors->get('apellido_materno')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Contraseña -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"  required autocomplete="new-password"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation"  required autocomplete="new-password"/>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Correo Alternativo -->
                <div class="mt-4">
                    <x-input-label for="correo_alternativo" :value="__('Correo Alternativo')" />
                    <x-text-input id="correo_alternativo" class="block mt-1 w-full" type="email" name="correo_alternativo"  />
                    <x-input-error :messages="$errors->get('correo_alternativo')" class="mt-2" />
                </div>

                <!-- Departamento -->
                <div class="mt-4">
                    <x-input-label for="departamento" :value="__('Departamento')" />
                    <select id="departamento" name="departamento" class="block mt-1 w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200">
                        @foreach ($departamentos as $departamento)
                            <option value="{{$departamento->id}}">{{$departamento->nombre}}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('departamento')" class="mt-2" />
                </div>

                <!-- Tipo de Usuario -->
                <div class="mt-4">
                    <x-input-label :value="__('Tipo de Usuario')" />
                    <div class="flex gap-2">
                        <label class="flex items-center">
                            <input type="radio" name="tipo_usuario" value="1" class="mr-1" required  onchange="toggleRoles()"> {{ __('Docente') }}
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="tipo_usuario" value="2" class="mr-1" required  onchange="toggleRoles()"> {{ __('Administrativo') }}
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="tipo_usuario" value="3" class="mr-1" required  onchange="toggleRoles()"> {{ __('Otro') }}
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('tipo_usuario')" class="mt-2" />
                </div>

                <!-- Roles -->
                <div class="mt-4" id="roles-container">
                    <x-input-label for="roles" :value="__('Rol')" />
                    <div class="mt-2">
                        @foreach ($roles as $role)
                            @if ($role->nombre != 'admin')
                                <label class="block mb-2 role-option">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        class="form-checkbox h-4 w-4 text-indigo-600" />
                                    <span class="ml-2 text-gray-700">{{ $role->nombre }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="mt-4">
                    <x-primary-button class="w-full">
                        {{ __('Registrar') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función para controlar la visibilidad de los roles
        function toggleRoles() {
            const tipoUsuario = document.querySelector('input[name="tipo_usuario"]:checked').value;
            const rolesContainer = document.getElementById('roles-container');
            const roleOptions = rolesContainer.querySelectorAll('.role-option');

            // Primero, desmarcar todos los checkboxes
            roleOptions.forEach(option => {
                const checkbox = option.querySelector('input');
                checkbox.checked = false; // Desmarcar checkbox
                option.style.display = 'none'; // Ocultar opción
            });

            // Mostrar roles dependiendo del tipo de usuario
            if (tipoUsuario == '1') { // Docente
                roleOptions.forEach(option => option.style.display = 'block');
            } else if (tipoUsuario == '2') { // Administrativo
                roleOptions.forEach(option => {
                    const roleId = option.querySelector('input').value;
                    if (roleId == '2' || roleId == '5') { // Jefe de departamento e Instructor
                        option.style.display = 'block';
                    }
                });
            } else if (tipoUsuario == '3') { // Otro
                roleOptions.forEach(option => {
                    const roleId = option.querySelector('input').value;
                    if (roleId == '5') { // Instructor
                        option.style.display = 'block';
                    }
                });
            }
        }

        // Ejecutar la función al cargar la página
        window.onload = toggleRoles;
    </script>
</x-app-layout>
