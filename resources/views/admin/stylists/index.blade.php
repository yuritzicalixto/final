<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Estilistas',
    ],
]">

    {{-- Encabezado con botón de crear --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Estilistas</h1>
            <p class="mt-1 text-sm text-gray-500">Gestiona los perfiles de tus estilistas y sus servicios asignados.</p>
        </div>
        <a href="{{ route('admin.stylists.create') }}"
           class="mt-4 sm:mt-0 inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo Estilista
        </a>
    </div>

    {{-- Mensaje cuando no hay estilistas --}}
    @if ($stylists->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-users-gear text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-700">No hay estilistas registrados</h3>
            <p class="mt-2 text-sm text-gray-500">Comienza creando el primer perfil de estilista.</p>
            <a href="{{ route('admin.stylists.create') }}"
               class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fa-solid fa-plus mr-2"></i>
                Crear estilista
            </a>
        </div>
    @else
        {{-- Grid de tarjetas de estilistas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach ($stylists as $stylist)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">

                    {{-- Cabecera de la tarjeta con foto --}}
                    <div class="relative">
                        {{-- Barra de estado --}}
                        <div class="absolute top-3 right-3 z-10">
                            @if ($stylist->status === 'available')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                    Disponible
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    No disponible
                                </span>
                            @endif
                        </div>

                        {{-- Foto del estilista --}}
                        <div class="h-48 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                            @if ($stylist->photo)
                                <img src="{{ Storage::url($stylist->photo) }}"
                                     alt="{{ $stylist->user->name ?? 'Estilista' }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fa-solid fa-user text-3xl text-indigo-300"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Cuerpo de la tarjeta --}}
                    <div class="p-5">
                        {{-- Nombre y especialidad --}}
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $stylist->user->name ?? 'Sin usuario' }}
                        </h3>
                        <p class="text-sm text-indigo-600 font-medium mt-0.5">
                            {{ $stylist->specialties }}
                        </p>

                        {{-- Información de contacto --}}
                        <div class="mt-3 space-y-1.5">
                            @if ($stylist->phone)
                                <p class="flex items-center text-sm text-gray-500">
                                    <i class="fa-solid fa-phone w-4 text-gray-400 mr-2"></i>
                                    {{ $stylist->phone }}
                                </p>
                            @endif
                            @if ($stylist->address)
                                <p class="flex items-center text-sm text-gray-500">
                                    <i class="fa-solid fa-location-dot w-4 text-gray-400 mr-2"></i>
                                    {{ Str::limit($stylist->address, 35) }}
                                </p>
                            @endif
                            @if ($stylist->user && $stylist->user->email)
                                <p class="flex items-center text-sm text-gray-500">
                                    <i class="fa-solid fa-envelope w-4 text-gray-400 mr-2"></i>
                                    {{ $stylist->user->email }}
                                </p>
                            @endif
                        </div>

                        {{-- Servicios asignados (chips) --}}
                        @if ($stylist->services->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach ($stylist->services->take(3) as $service)
                                    <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-md">
                                        {{ $service->name }}
                                    </span>
                                @endforeach
                                @if ($stylist->services->count() > 3)
                                    <span class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-600 text-xs rounded-md font-medium">
                                        +{{ $stylist->services->count() - 3 }} más
                                    </span>
                                @endif
                            </div>
                        @endif

                        {{-- Botones de acción --}}
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.stylists.show', $stylist) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-50 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
                                   title="Ver detalle">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('admin.stylists.edit', $stylist) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-50 text-gray-500 hover:bg-amber-50 hover:text-amber-600 transition-colors"
                                   title="Editar">
                                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                                </a>
                            </div>

                            {{-- Formulario de eliminación con confirmación --}}
                            <form action="{{ route('admin.stylists.destroy', $stylist) }}" method="POST"
                                  class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-600 transition-colors"
                                        title="Eliminar">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Script para confirmación de eliminación con SweetAlert2 --}}
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
