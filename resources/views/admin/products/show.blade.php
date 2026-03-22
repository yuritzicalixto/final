<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Productos',
        'route' => route('admin.products.index'),
    ],
    [
        'name'=> 'Ver',
    ],
]">

{{-- PLANTILLA --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $product->name }}
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Detalles completos del producto
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-2">
            <a href="{{ route('admin.products.edit', $product) }}"
               class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}"
                  method="POST"
                  class="inline"
                  onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- =====================================================
             COLUMNA PRINCIPAL (2/3)
        ====================================================== --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Imagen y badges --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="relative">
                    {{-- Imagen del producto --}}
                    <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-contain">
                    </div>

                    {{-- Badges de estado y stock --}}
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        {{-- Estado --}}
                        @if($product->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500 text-white shadow-lg">
                                <span class="w-2 h-2 mr-2 rounded-full bg-white animate-pulse"></span>
                                Activo
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-500 text-white shadow-lg">
                                <span class="w-2 h-2 mr-2 rounded-full bg-white"></span>
                                Inactivo
                            </span>
                        @endif

                        {{-- Stock badge --}}
                        @if($product->stock <= 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500 text-white shadow-lg">
                                Agotado
                            </span>
                        @elseif($product->stock < 5)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-500 text-white shadow-lg">
                                ¡Últimas {{ $product->stock }} unidades!
                            </span>
                        @endif
                    </div>

                    {{-- Categoría badge --}}
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/90 dark:bg-gray-800/90 text-gray-800 dark:text-gray-200 shadow-lg">
                            {{ $product->category->name ?? 'Sin categoría' }}
                        </span>
                    </div>

                    {{-- Precio destacado --}}
                    <div class="absolute bottom-4 right-4">
                        <div class="bg-amber-500 text-white px-4 py-2 rounded-lg shadow-lg">
                            <span class="text-sm opacity-90">Precio</span>
                            <p class="text-2xl font-bold">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Información del producto --}}
                <div class="p-6">
                    {{-- Marca --}}
                    @if($product->brand)
                        <p class="text-amber-600 dark:text-amber-400 font-medium mb-2">
                            {{ $product->brand }}
                        </p>
                    @endif

                    {{-- Nombre --}}
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $product->name }}
                    </h2>

                    {{-- Descripción --}}
                    @if($product->description)
                        <div class="prose dark:prose-invert max-w-none">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Descripción</h3>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 italic">
                            Este producto no tiene descripción.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- =====================================================
             COLUMNA LATERAL (1/3)
        ====================================================== --}}
        <div class="space-y-6">
            {{-- Tarjeta de Stock --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Inventario
                </h3>

                <div class="text-center py-4">
                    @if($product->stock <= 0)
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-red-100 dark:bg-red-900 mb-3">
                            <svg class="w-10 h-10 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-red-600 dark:text-red-400">0</p>
                        <p class="text-sm text-red-600 dark:text-red-400">Producto agotado</p>
                    @elseif($product->stock < 5)
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-yellow-100 dark:bg-yellow-900 mb-3">
                            <svg class="w-10 h-10 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $product->stock }}</p>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400">Stock bajo - ¡Reabastecer!</p>
                    @else
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 dark:bg-green-900 mb-3">
                            <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $product->stock }}</p>
                        <p class="text-sm text-green-600 dark:text-green-400">Unidades disponibles</p>
                    @endif
                </div>
            </div>

            {{-- Información adicional --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Información
                </h3>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $product->id }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">{{ $product->slug }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Categoría</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->category->name ?? 'Sin categoría' }}</dd>
                    </div>

                    @if($product->brand)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Marca</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->brand }}</dd>
                    </div>
                    @endif

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Creado</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->created_at->format('d/m/Y H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Última actualización</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $product->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Acciones rápidas --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Acciones Rápidas
                </h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="w-full inline-flex justify-center items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Producto
                    </a>

                    <a href="{{ route('admin.products.create') }}"
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Nuevo Producto
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                        </svg>
                        Volver al Listado
                    </a>
                </div>
            </div>
        </div>
    </div
{{-- PLANTILLA --}}

</x-admin-layout>
