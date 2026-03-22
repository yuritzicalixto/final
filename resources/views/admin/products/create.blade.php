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
        'name'=> 'Nuevo',
    ]
]">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Crear Nuevo Producto
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Completa la información para agregar un nuevo producto al catálogo
        </p>
    </div>

    {{-- =====================================================
         FORMULARIO
    ====================================================== --}}
    <form action="{{ route('admin.products.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- =====================================================
                 COLUMNA PRINCIPAL (2/3)
            ====================================================== --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Información básica --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Información del Producto
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nombre --}}
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre del producto <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="Ej: Shampoo Profesional Hidratante"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Categoría <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id"
                                    id="category_id"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Marca --}}
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Marca
                            </label>
                            <input type="text"
                                   name="brand"
                                   id="brand"
                                   value="{{ old('brand') }}"
                                   placeholder="Ej: GGS Professional"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-amber-500 focus:border-amber-500 @error('brand') border-red-500 @enderror">
                            @error('brand')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Descripción
                            </label>
                            <textarea name="description"
                                      id="description"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Describe las características y beneficios del producto..."
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-amber-500 focus:border-amber-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Máximo 1000 caracteres
                            </p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Precio y Stock --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Precio e Inventario
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Precio --}}
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Precio <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">$</span>
                                </div>
                                <input type="number"
                                       name="price"
                                       id="price"
                                       value="{{ old('price') }}"
                                       required
                                       min="0"
                                       max="999999.99"
                                       step="0.01"
                                       placeholder="0.00"
                                       class="w-full pl-8 pr-16 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-amber-500 focus:border-amber-500 @error('price') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">MXN</span>
                                </div>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Stock --}}
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Stock inicial <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number"
                                       name="stock"
                                       id="stock"
                                       value="{{ old('stock', 0) }}"
                                       required
                                       min="0"
                                       max="9999"
                                       placeholder="0"
                                       class="w-full pr-20 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-amber-500 focus:border-amber-500 @error('stock') border-red-500 @enderror">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">unidades</span>
                                </div>
                            </div>
                            @error('stock')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- =====================================================
                 COLUMNA LATERAL (1/3)
            ====================================================== --}}
            <div class="space-y-6">
                {{-- Estado --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Estado
                    </h2>
                    <select name="status"
                            id="status"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                            Activo - Visible en tienda
                        </option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                            Inactivo - Oculto en tienda
                        </option>
                    </select>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Los productos inactivos no se muestran a los clientes
                    </p>
                </div>

                {{-- Imagen --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Imagen del Producto
                    </h2>

                    {{-- Contenedor del preview (oculto inicialmente) --}}
                    <div id="image-preview-container" class="hidden mb-4 relative">
                        <img id="image-preview"
                             src=""
                             alt="Preview"
                             class="w-full h-48 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                        <button type="button"
                                id="remove-image-btn"
                                class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <p class="mt-2 text-xs text-center text-green-600 dark:text-green-400 font-medium">
                            ✓ Vista previa de la imagen seleccionada
                        </p>
                    </div>

                    {{-- Área de subida --}}
                    <label id="upload-label"
                           for="image"
                           class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-semibold">Clic para subir</span> o arrastra
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                PNG, JPG o WebP (Máx. 2MB)
                            </p>
                        </div>
                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="hidden">
                    </label>

                    @error('image')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- =====================================================
             BOTONES DE ACCIÓN
        ====================================================== --}}
        <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.products.index') }}"
               class="inline-flex justify-center items-center px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="inline-flex justify-center items-center px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Crear Producto
            </button>
        </div>
    </form>

    {{-- =====================================================
         SCRIPT INLINE PARA PREVIEW DE IMAGEN
    ====================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const imageInput = document.getElementById('image');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');
            const uploadLabel = document.getElementById('upload-label');
            const removeBtn = document.getElementById('remove-image-btn');

            // Función para mostrar el preview
            function showPreview(file) {
                if (!file.type.startsWith('image/')) {
                    alert('Por favor selecciona un archivo de imagen válido.');
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    alert('La imagen no debe superar los 2MB. El archivo seleccionado pesa ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
                    imageInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    uploadLabel.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }

            // Función para remover el preview
            function removePreview() {
                imageInput.value = '';
                previewImage.src = '';
                previewContainer.classList.add('hidden');
                uploadLabel.classList.remove('hidden');
            }

            // Event listener: selección de archivo
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    showPreview(file);
                }
            });

            // Event listener: botón remover
            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                removePreview();
            });

            // Drag and drop
            uploadLabel.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-amber-500', 'bg-amber-50', 'dark:bg-amber-900/20');
            });

            uploadLabel.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('border-amber-500', 'bg-amber-50', 'dark:bg-amber-900/20');
            });

            uploadLabel.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-amber-500', 'bg-amber-50', 'dark:bg-amber-900/20');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    imageInput.files = files;
                    showPreview(files[0]);
                }
            });
        });
    </script>

</x-admin-layout>
