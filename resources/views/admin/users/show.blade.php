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

    {{-- Detalle del usuario --}}
    <div class="card">

        {{-- Encabezado con avatar e info principal --}}
        <div class="flex items-center space-x-4 mb-6 pb-6 border-b border-gray-200">
            {{-- Avatar con inicial --}}
            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                <span class="text-2xl font-bold text-blue-600">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            </div>

            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">Registrado el {{ $user->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        {{-- Información de contacto --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <span class="block text-sm font-medium text-gray-500 mb-1">Correo electrónico</span>
                <span class="text-gray-900">{{ $user->email }}</span>
            </div>

            <div>
                <span class="block text-sm font-medium text-gray-500 mb-1">Teléfono</span>
                <span class="text-gray-900">{{ $user->phone ?? 'No registrado' }}</span>
            </div>
        </div>

        {{-- Roles asignados --}}
        <div class="mb-6">
            <span class="block text-sm font-medium text-gray-500 mb-2">Roles</span>
            <div class="flex flex-wrap gap-2">
                @forelse ($user->roles as $role)
                    @php
                        $colors = match($role->name) {
                            'admin'   => 'bg-red-100 text-red-700 ring-red-600/20',
                            'stylist' => 'bg-purple-100 text-purple-700 ring-purple-600/20',
                            'client'  => 'bg-sky-100 text-sky-700 ring-sky-600/20',
                            default   => 'bg-gray-100 text-gray-600 ring-gray-500/20',
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium ring-1 ring-inset {{ $colors }}">
                        {{ ucfirst($role->name) }}
                    </span>
                @empty
                    <span class="text-sm italic text-gray-400">Sin roles asignados</span>
                @endforelse
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex justify-end space-x-2 pt-4 border-t border-gray-200">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Volver
            </a>
            <a href="{{ route('admin.users.edit', $user) }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fa-solid fa-pen-to-square mr-2"></i>
                Editar
            </a>
        </div>
    </div>

</x-admin-layout>
