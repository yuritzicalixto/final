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
        'name'=> 'Nuevo',
    ]
]">

    <div class="card">
        {{-- Encabezado --}}
        <div class="flex items-center space-x-4 mb-8 pb-6 border-b border-gray-200">
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-green-100 flex items-center justify-center">
                <i class="fa-solid fa-user-plus text-xl text-green-600"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Nuevo usuario</h2>
                <p class="text-sm text-gray-500">Completa los datos para crear una cuenta</p>
            </div>
        </div>

        <x-validation-errors class="mb-6"/>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

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
                            value="{{ old('name') }}"
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
                            value="{{ old('email') }}"
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
                            value="{{ old('phone') }}"
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
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">
                    Seguridad
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    {{-- Contraseña --}}
                    <div>
                        <x-label class="mb-1">Contraseña</x-label>
                        <x-input
                            type="password"
                            name="password"
                            required
                            class="w-full"
                            placeholder="Mínimo 6 caracteres"
                        />
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div>
                        <x-label class="mb-1">Confirmar contraseña</x-label>
                        <x-input
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full"
                            placeholder="Repite la contraseña"
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
                    $oldRoles = old('roles', []);

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
                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->id }}"
                                class="peer sr-only"
                                {{ in_array($role->id, $oldRoles) ? 'checked' : '' }}
                            />
                            <div class="flex items-center space-x-3 p-3 rounded-lg border-2 border-gray-200 transition-all hover:border-gray-300 {{ $style['color'] }}">
                                <i class="{{ $style['icon'] }} text-gray-400"></i>
                                <span class="text-sm font-medium text-gray-700">{{ ucfirst($role->name) }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- ═══ Barra de acciones ═══ --}}
            <div class="flex items-center justify-end pt-6 border-t border-gray-200 space-x-3">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <x-button>
                    <i class="fa-solid fa-check mr-2 text-xs"></i>
                    Guardar
                </x-button>
            </div>
        </form>
    </div>

</x-admin-layout>
