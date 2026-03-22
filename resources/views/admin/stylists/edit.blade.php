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
        'name'=> $stylist->user->name ?? 'Editar',
    ]
]">

    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">Editar Estilista</h1>
        <p class="text-sm text-gray-500 mb-6">
            Actualiza la información del perfil de <strong>{{ $stylist->user->name ?? 'este estilista' }}</strong>.
        </p>

        <form action="{{ route('admin.stylists.update', $stylist) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- ============================================= --}}
                {{-- SECCIÓN: Información Principal --}}
                {{-- ============================================= --}}
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-700 mb-4">
                        <i class="fa-solid fa-user-tie text-indigo-500 mr-2"></i>
                        Información Principal
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Seleccionar usuario --}}
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Usuario <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" id="user_id"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">— Selecciona un usuario —</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $stylist->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Especialidad --}}
                        <div>
                            <label for="specialties" class="block text-sm font-medium text-gray-700 mb-1">
                                Especialidad <span class="text-red-500">*</span>
                            </label>
                            <select name="specialties" id="specialties"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">— Selecciona especialidad —</option>
                                <option value="Estilista Profesional"
                                    {{ old('specialties', $stylist->specialties) == 'Estilista Profesional' ? 'selected' : '' }}>
                                    Estilista Profesional
                                </option>
                                <option value="Maquillista"
                                    {{ old('specialties', $stylist->specialties) == 'Maquillista' ? 'selected' : '' }}>
                                    Maquillista
                                </option>
                                <option value="Manicurista"
                                    {{ old('specialties', $stylist->specialties) == 'Manicurista' ? 'selected' : '' }}>
                                    Manicurista
                                </option>
                            </select>
                            @error('specialties')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Teléfono --}}
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Teléfono
                            </label>
                            <input type="text" name="phone" id="phone"
                                   value="{{ old('phone', $stylist->phone) }}"
                                   placeholder="Ej: 2711234567"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="available"
                                    {{ old('status', $stylist->status) == 'available' ? 'selected' : '' }}>
                                    Disponible
                                </option>
                                <option value="unavailable"
                                    {{ old('status', $stylist->status) == 'unavailable' ? 'selected' : '' }}>
                                    No disponible
                                </option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Dirección --}}
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                Dirección
                            </label>
                            <input type="text" name="address" id="address"
                                   value="{{ old('address', $stylist->address) }}"
                                   placeholder="Ej: Av. Principal #123, Col. Centro, Orizaba, Ver."
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- SECCIÓN: Foto del Estilista --}}
                {{-- ============================================= --}}
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-700 mb-4">
                        <i class="fa-solid fa-camera text-indigo-500 mr-2"></i>
                        Foto del Estilista
                    </h2>

                    <div class="flex items-start gap-6">
                        {{-- Preview de la imagen (muestra la actual si existe) --}}
                        <div id="photo-preview"
                             class="flex-shrink-0 w-32 h-32 rounded-xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                            @if ($stylist->photo)
                                <div id="placeholder-icon" class="text-center hidden">
                                    <i class="fa-solid fa-image text-2xl text-gray-300"></i>
                                    <p class="text-xs text-gray-400 mt-1">Vista previa</p>
                                </div>
                                <img id="preview-img" src="{{ Storage::url($stylist->photo) }}" alt="Foto actual"
                                     class="w-full h-full object-cover">
                            @else
                                <div id="placeholder-icon" class="text-center">
                                    <i class="fa-solid fa-image text-2xl text-gray-300"></i>
                                    <p class="text-xs text-gray-400 mt-1">Vista previa</p>
                                </div>
                                <img id="preview-img" src="#" alt="Preview"
                                     class="w-full h-full object-cover hidden">
                            @endif
                        </div>

                        <div class="flex-1">
                            <input type="file" name="photo" id="photo" accept="image/*"
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-medium
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100
                                          cursor-pointer">
                            <p class="mt-2 text-xs text-gray-400">
                                Formatos: JPG, JPEG, PNG, WebP. Máx: 2 MB.
                                @if ($stylist->photo)
                                    Sube una nueva imagen para reemplazar la actual.
                                @endif
                            </p>
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ============================================= --}}
                {{-- SECCIÓN: Servicios que puede realizar --}}
                {{-- ============================================= --}}
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-700 mb-4">
                        <i class="fa-solid fa-scissors text-indigo-500 mr-2"></i>
                        Servicios Asignados
                    </h2>
                    <p class="text-sm text-gray-500 mb-4">
                        Selecciona los servicios que este estilista puede realizar.
                    </p>

                    @if ($services->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($services as $service)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/50 transition-colors cursor-pointer
                                    {{ in_array($service->id, old('services', $assignedServices)) ? 'border-indigo-300 bg-indigo-50/30' : '' }}">
                                    <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                           {{ in_array($service->id, old('services', $assignedServices)) ? 'checked' : '' }}
                                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-700 truncate">{{ $service->name }}</p>
                                        @if ($service->category)
                                            <p class="text-xs text-gray-400">{{ $service->category }}</p>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400">No hay servicios registrados aún.</p>
                    @endif
                </div>

                {{-- ============================================= --}}
                {{-- BOTONES --}}
                {{-- ============================================= --}}
                <div class="p-6 bg-gray-50 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.stylists.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Actualizar Estilista
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Script para preview de imagen --}}
    @push('js')
        <script>
            document.getElementById('photo').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewImg = document.getElementById('preview-img');
                        const placeholderIcon = document.getElementById('placeholder-icon');
                        previewImg.src = e.target.result;
                        previewImg.classList.remove('hidden');
                        if (placeholderIcon) {
                            placeholderIcon.classList.add('hidden');
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        </script>
    @endpush

</x-admin-layout>
