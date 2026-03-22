<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Estilistas',
        'route' => route('admin.stylists.index'),
    ],
    [
        'name'=> $stylist->user->name ?? 'Detalle',
    ]
]">

    <div class="max-w-4xl mx-auto">

        {{-- Cabecera con acciones rápidas --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Perfil del Estilista</h1>
                <p class="mt-1 text-sm text-gray-500">Información completa del perfil.</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center gap-3">
                <a href="{{ route('admin.stylists.edit', $stylist) }}"
                   class="inline-flex items-center px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors shadow-sm">
                    <i class="fa-solid fa-pen-to-square mr-2"></i>
                    Editar
                </a>
                <form action="{{ route('admin.stylists.destroy', $stylist) }}" method="POST" class="inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors shadow-sm">
                        <i class="fa-solid fa-trash-can mr-2"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Banner con foto y datos principales --}}
            <div class="relative">
                {{-- Fondo degradado --}}
                <div class="h-36 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600"></div>

                {{-- Contenido sobre el banner --}}
                <div class="px-6 pb-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-end gap-4 -mt-16">
                        {{-- Foto --}}
                        <div class="flex-shrink-0 w-28 h-28 rounded-xl border-4 border-white shadow-md bg-white overflow-hidden">
                            @if ($stylist->photo)
                                <img src="{{ Storage::url($stylist->photo) }}"
                                     alt="{{ $stylist->user->name ?? 'Estilista' }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-indigo-50 flex items-center justify-center">
                                    <i class="fa-solid fa-user text-3xl text-indigo-300"></i>
                                </div>
                            @endif
                        </div>

                        {{-- Nombre y rol --}}
                        <div class="flex-1 pb-1">
                            <h2 class="text-xl font-bold text-gray-800">
                                {{ $stylist->user->name ?? 'Sin nombre' }}
                            </h2>
                            <p class="text-sm text-indigo-600 font-medium">
                                {{ $stylist->specialties ?? 'Sin especialidad' }}
                            </p>
                        </div>

                        {{-- Estado --}}
                        <div class="pb-1">
                            @if ($stylist->status === 'available')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-emerald-100 text-emerald-700">
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                    Disponible
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                    No disponible
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información detallada --}}
            <div class="p-6 border-t border-gray-100">
                <h3 class="text-base font-semibold text-gray-700 mb-4">
                    <i class="fa-solid fa-circle-info text-indigo-500 mr-2"></i>
                    Información de Contacto
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Email --}}
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Correo electrónico</p>
                        <p class="text-sm text-gray-700 flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-gray-400"></i>
                            {{ $stylist->user->email ?? 'No registrado' }}
                        </p>
                    </div>

                    {{-- Teléfono --}}
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Teléfono</p>
                        <p class="text-sm text-gray-700 flex items-center gap-2">
                            <i class="fa-solid fa-phone text-gray-400"></i>
                            {{ $stylist->phone ?? 'No registrado' }}
                        </p>
                    </div>

                    {{-- Dirección --}}
                    <div class="sm:col-span-2">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Dirección</p>
                        <p class="text-sm text-gray-700 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-gray-400"></i>
                            {{ $stylist->address ?? 'No registrada' }}
                        </p>
                    </div>

                    {{-- Fecha de registro --}}
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Registrado el</p>
                        <p class="text-sm text-gray-700 flex items-center gap-2">
                            <i class="fa-solid fa-calendar text-gray-400"></i>
                            {{ $stylist->created_at->format('d/m/Y') }}
                        </p>
                    </div>

                    {{-- Última actualización --}}
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Última actualización</p>
                        <p class="text-sm text-gray-700 flex items-center gap-2">
                            <i class="fa-solid fa-clock text-gray-400"></i>
                            {{ $stylist->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Servicios asignados --}}
            <div class="p-6 border-t border-gray-100">
                <h3 class="text-base font-semibold text-gray-700 mb-4">
                    <i class="fa-solid fa-scissors text-indigo-500 mr-2"></i>
                    Servicios que Realiza
                </h3>

                @if ($stylist->services->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($stylist->services as $service)
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-check text-sm text-indigo-600"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-700 truncate">{{ $service->name }}</p>
                                    @if ($service->category)
                                        <p class="text-xs text-gray-400">{{ $service->category }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400">Este estilista no tiene servicios asignados todavía.</p>
                @endif
            </div>

        </div>

        {{-- Botón de regreso --}}
        <div class="mt-6">
            <a href="{{ route('admin.stylists.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Volver al listado
            </a>
        </div>

    </div>

    {{-- Script para confirmación de eliminación --}}
    @push('js')
        <script>
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Se eliminará el perfil de este estilista. Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush

</x-admin-layout>
