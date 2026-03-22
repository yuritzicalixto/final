<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Usuarios',
        'route' => route('admin.users.index'),
    ],
    [
        'name'=> $user->name,
    ]
]">

    <div class="card">
        {{-- Encabezado con avatar e info del usuario --}}
        <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-gray-200">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                <span class="text-xl font-bold text-blue-600">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Editar usuario</h2>
                <p class="text-sm text-gray-500">Registrado el {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <x-validation-errors class="mb-6"/>

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ═══ Sección: Información personal ═══ --}}
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">
                    Información personal
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    {{-- Nombre --}}
                    <div>
                        <x-label class="mb-1">Nombre</x-label>
                        <x-input
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full"
                            placeholder="Nombre completo"
                        />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-label class="mb-1">Correo electrónico</x-label>
                        <x-input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full"
                            placeholder="correo@ejemplo.com"
                        />
                    </div>

                    {{-- Teléfono --}}
                    <div>
                        <x-label class="mb-1">Teléfono</x-label>
                        <x-input
                            type="tel"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            required
                            class="w-full"
                            placeholder="Ej: 9511234567"
                            maxlength="15"
                        />
                    </div>
                </div>
            </div>

            {{-- ═══ Sección: Seguridad ═══ --}}
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-1">
                    Seguridad
                </h3>
                <p class="text-xs text-gray-400 mb-4">Dejar vacío para mantener la contraseña actual</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    {{-- Contraseña --}}
                    <div>
                        <x-label class="mb-1">Nueva contraseña</x-label>
                        <x-input
                            type="password"
                            name="password"
                            class="w-full"
                            placeholder="••••••••"
                        />
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div>
                        <x-label class="mb-1">Confirmar contraseña</x-label>
                        <x-input
                            type="password"
                            name="password_confirmation"
                            class="w-full"
                            placeholder="••••••••"
                        />
                    </div>
                </div>
            </div>

            {{-- ═══ Sección: Roles ═══ --}}
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">
                    Roles
                </h3>

                @php
                    $userRoleIds = old('roles', $user->roles->pluck('id')->toArray());

                    // Icono y color por rol para darle identidad visual a cada opción
                    $roleStyles = [
                        'admin'   => ['icon' => 'fa-solid fa-shield-halved',   'color' => 'peer-checked:border-red-400 peer-checked:bg-red-50'],
                        'stylist' => ['icon' => 'fa-solid fa-scissors',        'color' => 'peer-checked:border-purple-400 peer-checked:bg-purple-50'],
                        'client'  => ['icon' => 'fa-solid fa-user',            'color' => 'peer-checked:border-sky-400 peer-checked:bg-sky-50'],
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach ($roles as $role)
                        @php
                            $style = $roleStyles[$role->name] ?? ['icon' => 'fa-solid fa-tag', 'color' => 'peer-checked:border-gray-400 peer-checked:bg-gray-50'];
                        @endphp
                        <label class="relative cursor-pointer">
                            {{-- Checkbox real (oculto visualmente pero funcional) --}}
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->id }}"
                                class="peer sr-only"
                                {{ in_array($role->id, $userRoleIds) ? 'checked' : '' }}
                            />
                            {{-- Tarjeta visual que reacciona al estado checked del input --}}
                            <div class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 transition-all hover:border-gray-300 {{ $style['color'] }}">
                                <i class="{{ $style['icon'] }} text-gray-400 peer-checked:text-current"></i>
                                <span class="text-sm font-medium text-gray-700">{{ ucfirst($role->name) }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- ═══ Barra de acciones ═══ --}}
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                {{-- Eliminar a la izquierda para evitar clics accidentales --}}
                <button type="button" onclick="confirmDelete()"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                    <i class="fa-solid fa-trash-can mr-2 text-xs"></i>
                    Eliminar
                </button>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <x-button>
                        <i class="fa-solid fa-check mr-2 text-xs"></i>
                        Actualizar
                    </x-button>
                </div>
            </div>
        </form>
    </div>

    {{-- Formulario oculto para eliminación --}}
    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
    </form>

    @push('js')
        <script>
            function confirmDelete() {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡No podrás revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '¡Sí, bórralo!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm').submit();
                    }
                });
            }
        </script>
    @endpush

</x-admin-layout>
