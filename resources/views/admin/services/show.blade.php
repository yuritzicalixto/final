{{-- resources/views/admin/services/show.blade.php --}}

<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Servicios',
        'route' => route('admin.services.index'),
    ],
    [
        'name'=> $service->name,
    ]
]">

    <div class="max-w-4xl mx-auto">

        {{-- Encabezado con acciones --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $service->name }}</h2>
                <div class="mt-2 flex items-center gap-3">
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

                    @if($service->tag)
                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">
                            {{ $service->tag }}
                        </span>
                    @endif

                    @if($service->is_highlighted)
                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">
                            <i class="fa-solid fa-star text-[10px] mr-1"></i>Destacado
                        </span>
                    @endif
                </div>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center gap-2">
                <a href="{{ route('admin.services.edit', $service) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                    Editar
                </a>
                <form action="{{ route('admin.services.destroy', $service) }}"
                      method="POST"
                      onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-gray-800 border border-red-300 dark:border-red-800 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <i class="fa-solid fa-trash text-xs"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

        {{-- Contenido --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            {{-- Imagen --}}
            <div class="w-full h-64 md:h-80 bg-gray-100 dark:bg-gray-700">
                <img src="{{ $service->image_url }}"
                     alt="{{ $service->name }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- Detalles --}}
            <div class="p-6 space-y-6">

                {{-- Info rápida: Categoría, Precio, Duración --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Categoría</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $service->category ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Precio</p>
                        <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">{{ $service->price_formatted }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Duración</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $service->duration_formatted }}</p>
                    </div>
                </div>

                {{-- Descripción --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-2">Descripción</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $service->description }}</p>
                </div>

                {{-- Características --}}
                @if($service->features)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">Características</h3>
                        <div class="space-y-2">
                            @foreach($service->features_array as $feature)
                                <div class="flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-check text-[10px] text-indigo-600 dark:text-indigo-400"></i>
                                    </span>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Metadatos --}}
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-wrap gap-6 text-xs text-gray-400">
                        <span>Creado: {{ $service->created_at->format('d/m/Y H:i') }}</span>
                        <span>Actualizado: {{ $service->updated_at->format('d/m/Y H:i') }}</span>
                        <span>Slug: {{ $service->slug }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botón volver --}}
        <div class="mt-6">
            <a href="{{ route('admin.services.index') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Volver a la lista
            </a>
        </div>
    </div>

</x-admin-layout>
