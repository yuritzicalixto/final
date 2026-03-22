<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Notificaciones', 'route' => route('admin.notifications.index')],
    ['name' => 'Nueva Notificación'],
]">

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6">
                <i class="fa-solid fa-paper-plane mr-2"></i>
                Enviar Notificación Push
            </h3>

            <form action="{{ route('admin.notifications.store') }}"
                  method="POST"
                  class="swal-confirm-form"
                  data-swal-title="¿Enviar notificación?"
                  data-swal-text="Se enviará a los destinatarios seleccionados. Esta acción no se puede deshacer."
                  data-swal-icon="question"
                  data-swal-confirm="Sí, enviar"
                  data-swal-cancel="Cancelar"
                  data-swal-color="#2563eb">
                @csrf

                {{-- Título --}}
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: ¡Promoción especial!" maxlength="100" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mensaje --}}
                <div class="mb-4">
                    <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Mensaje <span class="text-red-500">*</span>
                    </label>
                    <textarea name="body" id="body" rows="3"
                              class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Ej: 20% de descuento en todos los servicios este fin de semana." maxlength="255" required>{{ old('body') }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Máximo 255 caracteres.</p>
                    @error('body')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- URL (opcional) --}}
                <div class="mb-6">
                    <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        URL al hacer clic <span class="text-gray-400">(opcional)</span>
                    </label>
                    <input type="text" name="url" id="url" value="{{ old('url') }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: /client/appointments">
                    @error('url')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Separador --}}
                <hr class="mb-6 dark:border-gray-700">

                {{-- Destinatarios --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Destinatarios <span class="text-red-500">*</span>
                    </label>

                    <div class="space-y-3">
                        {{-- Opción: Todos --}}
                        <label class="flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <input type="radio" name="audience" value="all"
                                   {{ old('audience', 'all') === 'all' ? 'checked' : '' }}
                                   class="text-blue-600 focus:ring-blue-500"
                                   onchange="document.getElementById('clients-list').classList.add('hidden')">
                            <span class="ml-3">
                                <span class="font-medium text-gray-800 dark:text-white">Todos los clientes suscritos</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">
                                    {{ $subscribedClients->count() }} cliente(s) con notificaciones activas
                                </span>
                            </span>
                        </label>

                        {{-- Opción: Seleccionar --}}
                        <label class="flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-600 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <input type="radio" name="audience" value="selected"
                                   {{ old('audience') === 'selected' ? 'checked' : '' }}
                                   class="text-blue-600 focus:ring-blue-500"
                                   onchange="document.getElementById('clients-list').classList.remove('hidden')">
                            <span class="ml-3">
                                <span class="font-medium text-gray-800 dark:text-white">Seleccionar clientes específicos</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">
                                    Elige a quién enviar la notificación
                                </span>
                            </span>
                        </label>
                    </div>

                    @error('audience')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lista de clientes (visible solo si audience=selected) --}}
                <div id="clients-list" class="{{ old('audience') === 'selected' ? '' : 'hidden' }} mb-6">
                    <div class="border border-gray-200 dark:border-gray-600 rounded-lg max-h-64 overflow-y-auto">
                        {{-- Buscador --}}
                        <div class="sticky top-0 bg-white dark:bg-gray-800 p-3 border-b dark:border-gray-600">
                            <input type="text" id="search-clients" placeholder="Buscar cliente..."
                                   class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div id="clients-container" class="p-2 space-y-1">
                            @forelse($subscribedClients as $client)
                                <label class="client-item flex items-center p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer" data-name="{{ strtolower($client->name) }}" data-email="{{ strtolower($client->email) }}">
                                    <input type="checkbox" name="users[]" value="{{ $client->id }}"
                                           {{ in_array($client->id, old('users', [])) ? 'checked' : '' }}
                                           class="text-blue-600 rounded focus:ring-blue-500">
                                    <span class="ml-3">
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $client->name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ $client->email }}</span>
                                    </span>
                                </label>
                            @empty
                                <p class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No hay clientes con notificaciones push activas.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    @error('users')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.notifications.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        <i class="fa-solid fa-paper-plane mr-1"></i>
                        Enviar Notificación
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para filtrar clientes --}}
    <script>
        document.getElementById('search-clients')?.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.client-item').forEach(item => {
                const name = item.dataset.name;
                const email = item.dataset.email;
                item.style.display = (name.includes(query) || email.includes(query)) ? '' : 'none';
            });
        });
    </script>

</x-admin-layout>
