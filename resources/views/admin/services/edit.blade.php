{{-- resources/views/admin/services/edit.blade.php --}}

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
        'name'=> 'Editar',
    ]
]">

    <div class="max-w-4xl mx-auto">

        {{-- Encabezado --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Editar Servicio</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Modifica la información de <strong>{{ $service->name }}</strong>.</p>
        </div>

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fa-solid fa-circle-exclamation text-red-600 dark:text-red-400"></i>
                    <span class="text-sm font-medium text-red-700 dark:text-red-300">Corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('admin.services.update', $service) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 space-y-6">

                {{-- Sección: Información básica --}}
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Información del Servicio
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nombre --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nombre del servicio <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $service->name) }}"
                                   placeholder="Ej: Colorimetría"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   required>
                            @error('name')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Categoría <span class="text-red-500">*</span>
                            </label>
                            <select id="category"
                                    name="category"
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    required>
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $service->category) === $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descripción <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  placeholder="Describe brevemente en qué consiste el servicio..."
                                  class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                                  required>{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Características --}}
                    <div class="mt-6">
                        <label for="features" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Características
                            <span class="text-xs font-normal text-gray-400">(sepáralas con | )</span>
                        </label>
                        <input type="text"
                               id="features"
                               name="features"
                               value="{{ old('features', $service->features) }}"
                               placeholder="Ej: Análisis profesional de tono|Propuesta personalizada|Plan de mantenimiento"
                               class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <p class="mt-1 text-xs text-gray-400">Se mostrarán como viñetas en la tarjeta del servicio.</p>
                        @error('features')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Sección: Precio y Duración --}}
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Precio y Duración
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Precio --}}
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Precio (MXN) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                <input type="number"
                                       id="price"
                                       name="price"
                                       value="{{ old('price', $service->price) }}"
                                       placeholder="0.00"
                                       step="0.01"
                                       min="0"
                                       class="w-full pl-8 pr-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                       required>
                            </div>
                            @error('price')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Duración --}}
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Duración (minutos) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="duration"
                                   name="duration"
                                   value="{{ old('duration', $service->duration) }}"
                                   placeholder="60"
                                   min="5"
                                   max="480"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   required>
                            @error('duration')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Sección: Imagen y Apariencia --}}
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                        Imagen y Apariencia
                    </h3>

                    {{-- Imagen actual + subir nueva --}}
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Imagen del servicio
                        </label>

                        {{-- Mostrar imagen actual si existe --}}
                        @if($service->image)
                            <div class="mb-3 flex items-center gap-4">
                                <div class="w-24 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img src="{{ $service->image_url }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                                </div>
                                <p class="text-xs text-gray-400">Imagen actual. Sube una nueva para reemplazarla.</p>
                            </div>
                        @endif

                        <div class="relative">
                            <input type="file"
                                   id="image"
                                   name="image"
                                   accept="image/jpg,image/jpeg,image/png,image/webp"
                                   class="hidden"
                                   onchange="previewImage(event)">

                            <label for="image"
                                   class="flex flex-col items-center justify-center w-full h-40 bg-gray-50 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
                                <div id="uploadPlaceholder" class="text-center">
                                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Haz clic para subir una nueva imagen</p>
                                    <p class="text-xs text-gray-400 mt-1">JPG, PNG o WEBP (máx. 2MB)</p>
                                </div>
                                <img id="imagePreview"
                                     class="hidden w-full h-full object-cover rounded-lg"
                                     alt="Preview">
                            </label>
                        </div>
                        @error('image')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        {{-- Etiqueta/Tag --}}
                        <div>
                            <label for="tag" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Etiqueta
                                <span class="text-xs font-normal text-gray-400">(opcional)</span>
                            </label>
                            <input type="text"
                                   id="tag"
                                   name="tag"
                                   value="{{ old('tag', $service->tag) }}"
                                   placeholder="Ej: Popular, Signature, Premium"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <p class="mt-1 text-xs text-gray-400">Se muestra como badge sobre la imagen del servicio.</p>
                        </div>

                        {{-- Estado --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select id="status"
                                    name="status"
                                    class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    required>
                                <option value="active" {{ old('status', $service->status) === 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status', $service->status) === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    {{-- Destacado --}}
                    <div class="mt-6">
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   name="is_highlighted"
                                   value="1"
                                   {{ old('is_highlighted', $service->is_highlighted) ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500">
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Servicio destacado</span>
                                <p class="text-xs text-gray-400">Se mostrará con borde dorado en el sitio web.</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.services.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    {{-- Script para preview de imagen --}}
    @push('js')
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const placeholder = document.getElementById('uploadPlaceholder');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    @endpush

</x-admin-layout>
