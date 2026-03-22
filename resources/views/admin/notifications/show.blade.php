<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Notificaciones', 'route' => route('admin.notifications.index')],
    ['name' => 'Detalle'],
]">

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">

            {{-- Encabezado --}}
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                        {{ $notification->title }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Enviada el {{ $notification->created_at->format('d/m/Y \a \l\a\s H:i') }}
                    </p>
                </div>

                @php
                    $colors = [
                        'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                        'blue'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                        'amber'  => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300',
                        'gray'   => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                    ];
                    $color = $colors[$notification->type_color] ?? $colors['gray'];
                @endphp
                <span class="px-3 py-1 text-sm font-medium rounded-full {{ $color }}">
                    {{ $notification->type_label }}
                </span>
            </div>

            {{-- Contenido --}}
            <div class="space-y-4 mb-6">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mensaje</p>
                    <p class="mt-1 text-gray-800 dark:text-white">{{ $notification->body }}</p>
                </div>

                @if($notification->url)
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">URL de destino</p>
                        <p class="mt-1 text-blue-600 dark:text-blue-400">{{ $notification->url }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Enviado por</p>
                        <p class="mt-1 text-gray-800 dark:text-white">
                            {{ $notification->sender?->name ?? 'Sistema (automático)' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Destinatarios</p>
                        <p class="mt-1 text-gray-800 dark:text-white">
                            {{ $notification->recipients_count }} usuario(s)
                            <span class="text-sm text-gray-500">({{ $notification->audience === 'all' ? 'Todos' : 'Seleccionados' }})</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Lista de destinatarios (solo si fue envío selectivo) --}}
            @if($recipients && $recipients->isNotEmpty())
                <hr class="mb-4 dark:border-gray-700">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    <i class="fa-solid fa-users mr-1"></i> Destinatarios seleccionados
                </h4>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($recipients as $recipient)
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <i class="fa-solid fa-user mr-2 text-gray-400"></i>
                            {{ $recipient->name }}
                            <span class="ml-2 text-gray-400">{{ $recipient->email }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Botón regresar --}}
            <div class="mt-6 pt-4 border-t dark:border-gray-700">
                <a href="{{ route('admin.notifications.index') }}"
                   class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Volver al historial
                </a>
            </div>
        </div>
    </div>

</x-admin-layout>
