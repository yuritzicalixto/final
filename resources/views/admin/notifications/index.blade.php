<x-admin-layout :breadcrumbs="[
    ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['name' => 'Notificaciones'],
]">

    {{-- TARJETAS DE ESTADÍSTICAS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Usuarios Suscritos --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-full p-3">
                    <i class="fa-solid fa-users text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Usuarios Suscritos</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['subscribed_users'] }}</p>
                </div>
            </div>
        </div>

        {{-- Enviadas Hoy --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-full p-3">
                    <i class="fa-solid fa-paper-plane text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Enviadas Hoy</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['sent_today'] }}</p>
                </div>
            </div>
        </div>

        {{-- Enviadas Este Mes --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 rounded-full p-3">
                    <i class="fa-solid fa-chart-bar text-purple-600 dark:text-purple-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Este Mes</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['sent_this_month'] }}</p>
                </div>
            </div>
        </div>

        {{-- Total Histórico --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-amber-100 dark:bg-amber-900 rounded-full p-3">
                    <i class="fa-solid fa-bell text-amber-600 dark:text-amber-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Enviadas</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total_sent'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- BARRA DE ACCIONES --}}
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Historial de Notificaciones</h3>
        <a href="{{ route('admin.notifications.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            <i class="fa-solid fa-plus mr-2"></i>
            Nueva Notificación
        </a>
    </div>

    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- TABLA DE HISTORIAL --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">Fecha</th>
                        <th class="px-6 py-3">Título</th>
                        <th class="px-6 py-3">Tipo</th>
                        <th class="px-6 py-3">Destinatarios</th>
                        <th class="px-6 py-3">Enviado por</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $notification->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white max-w-xs truncate">
                                {{ $notification->title }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'blue'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'amber'  => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300',
                                        'gray'   => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
                                    ];
                                    $color = $colors[$notification->type_color] ?? $colors['gray'];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $color }}">
                                    {{ $notification->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center">
                                    <i class="fa-solid fa-user mr-1"></i>
                                    {{ $notification->recipients_count }}
                                    <span class="ml-1 text-xs text-gray-400">
                                        ({{ $notification->audience === 'all' ? 'Todos' : 'Seleccionados' }})
                                    </span>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $notification->sender?->name ?? 'Sistema' }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.notifications.show', $notification) }}"
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <i class="fa-solid fa-bell-slash text-3xl mb-2 block"></i>
                                No se han enviado notificaciones aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($notifications->hasPages())
            <div class="px-6 py-3 border-t dark:border-gray-700">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</x-admin-layout>
