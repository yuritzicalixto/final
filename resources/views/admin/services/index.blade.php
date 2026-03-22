{{-- resources/views/admin/services/index.blade.php --}}

<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Servicios',
    ],
]">

    {{-- Encabezado con botón de crear --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Servicios</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Administra los servicios que se muestran en el sitio web.</p>
        </div>
        <a href="{{ route('admin.services.create') }}"
           class="mt-4 sm:mt-0 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i>
            Nuevo Servicio
        </a>
    </div>

    {{-- Mensaje de éxito (flash) --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <i class="fa-solid fa-circle-check text-green-600 dark:text-green-400"></i>
            <span class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tabla de servicios --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

        @if($services->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">Servicio</th>
                            <th class="px-6 py-4">Categoría</th>
                            <th class="px-6 py-4">Duración</th>
                            <th class="px-6 py-4">Precio</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($services as $service)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                {{-- Nombre + Imagen thumbnail --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100 dark:bg-gray-700">
                                            <img src="{{ $service->image_url }}"
                                                 alt="{{ $service->name }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800 dark:text-white">{{ $service->name }}</p>
                                            @if($service->tag)
                                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">
                                                    {{ $service->tag }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Categoría --}}
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $service->category ?? '—' }}
                                </td>

                                {{-- Duración --}}
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $service->duration_formatted }}
                                </td>

                                {{-- Precio --}}
                                <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">
                                    {{ $service->price_formatted }}
                                </td>

                                {{-- Estado --}}
                                <td class="px-6 py-4">
                                    @if($service->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-600/30 text-gray-600 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Ver --}}
                                        <a href="{{ route('admin.services.show', $service) }}"
                                           class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                           title="Ver detalle">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('admin.services.edit', $service) }}"
                                           class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors"
                                           title="Editar">
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </a>

                                        {{-- Eliminar --}}
                                        <form action="{{ route('admin.services.destroy', $service) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                    title="Eliminar">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- Estado vacío --}}
            <div class="flex flex-col items-center justify-center py-16 px-6">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-shop text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Sin servicios</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Aún no has creado ningún servicio.</p>
                <a href="{{ route('admin.services.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Crear primer servicio
                </a>
            </div>
        @endif
    </div>

</x-admin-layout>
