<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Apartados',
    ],
]">

{{-- Mensajes flash --}}
@if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
        <p class="text-green-800 dark:text-green-300"><i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}</p>
    </div>
@endif
@if(session('error'))
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
        <p class="text-red-800 dark:text-red-300"><i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}</p>
    </div>
@endif

{{-- ═══════════════════════════════════════════
     ESTADÍSTICAS
     ═══════════════════════════════════════════ --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 text-center">
        <div class="text-2xl font-bold text-yellow-600">{{ $stats['active'] }}</div>
        <div class="text-xs text-yellow-700 dark:text-yellow-400">Activos</div>
    </div>
    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center">
        <div class="text-2xl font-bold text-red-600">{{ $stats['expiring'] }}</div>
        <div class="text-xs text-red-700 dark:text-red-400">Por vencer</div>
    </div>
    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center">
        <div class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</div>
        <div class="text-xs text-green-700 dark:text-green-400">Completados</div>
    </div>
    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center">
        <div class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ $stats['expired'] }}</div>
        <div class="text-xs text-gray-500 dark:text-gray-400">Expirados</div>
    </div>
    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 text-center">
        <div class="text-2xl font-bold text-red-500">{{ $stats['cancelled'] }}</div>
        <div class="text-xs text-red-600 dark:text-red-400">Cancelados</div>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     FILTROS Y BÚSQUEDA
     ═══════════════════════════════════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Buscar por # apartado, nombre o email del cliente..."
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <select name="status" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todos los estados</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completados</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirados</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelados</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <i class="fa-solid fa-search mr-1"></i> Filtrar
            </button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.reservations.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- Botón expirar vencidos --}}
    @if($stats['active'] > 0)
        <div class="px-4 py-2 bg-amber-50 dark:bg-amber-900/10 flex items-center justify-between">
            <span class="text-sm text-amber-700 dark:text-amber-400">
                <i class="fa-solid fa-clock mr-1"></i> Puedes expirar manualmente los apartados vencidos.
            </span>
            {{-- <form action="{{ route('admin.reservations.expire-all') }}" method="POST"
                  onsubmit="return confirm('¿Expirar todos los apartados vencidos? El stock será restaurado.')">
                @csrf
                <button type="submit" class="text-sm px-3 py-1 bg-amber-500 text-white rounded hover:bg-amber-600 transition">
                    Expirar vencidos
                </button>
            </form> --}}

            <form action="{{ route('admin.reservations.expire-all') }}" method="POST"
                  class="swal-confirm-form"
                  data-swal-title="¿Expirar apartados vencidos?"
                  data-swal-text="Se expirarán todos los apartados que hayan superado su fecha de vencimiento. El stock será restaurado automáticamente."
                  data-swal-icon="warning"
                  data-swal-confirm="Sí, expirar todos"
                  data-swal-cancel="No, cancelar"
                  data-swal-color="#d97706">
                @csrf
                <button type="submit" class="text-sm px-3 py-1 bg-amber-500 text-white rounded hover:bg-amber-600 transition">
                    Expirar vencidos
                </button>
            </form>

        </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════
     TABLA DE APARTADOS
     ═══════════════════════════════════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    @if($reservations->isEmpty())
        <div class="p-12 text-center text-gray-500 dark:text-gray-400">
            <i class="fa-solid fa-inbox text-4xl mb-4"></i>
            <p>No se encontraron apartados.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">No. Apartado</th>
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">Productos</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Fecha apartado</th>
                        <th class="px-4 py-3">Vencimiento</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($reservations as $reservation)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50
                            {{ $reservation->is_expiring ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">
                            {{-- Número --}}
                            <td class="px-4 py-3">
                                <span class="font-mono font-semibold text-gray-900 dark:text-white">
                                    {{ $reservation->reservation_number }}
                                </span>
                                @if($reservation->is_expiring)
                                    <span class="block text-xs text-red-500 mt-1">
                                        <i class="fa-solid fa-exclamation-triangle"></i> ¡Vence pronto!
                                    </span>
                                @endif
                            </td>

                            {{-- Cliente --}}
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $reservation->client->name }}</p>
                                <p class="text-xs text-gray-500">{{ $reservation->client->email }}</p>
                            </td>

                            {{-- Productos --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($reservation->items->take(2) as $item)
                                        <span class="text-xs bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-1.5 py-0.5 rounded">
                                            {{ Str::limit($item->product->name, 20) }} (x{{ $item->quantity }})
                                        </span>
                                    @endforeach
                                    @if($reservation->items->count() > 2)
                                        <span class="text-xs text-gray-500">+{{ $reservation->items->count() - 2 }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Total --}}
                            <td class="px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                {{ $reservation->total_formatted }}
                            </td>

                            {{-- Fecha apartado --}}
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                {{ $reservation->reservation_date->format('d/m/Y') }}
                            </td>

                            {{-- Vencimiento --}}
                            <td class="px-4 py-3">
                                <span class="{{ $reservation->is_expiring ? 'text-red-600 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                                    {{ $reservation->expiration_date->format('d/m/Y') }}
                                </span>
                                @if($reservation->status === 'active')
                                    <span class="block text-xs {{ $reservation->is_expiring ? 'text-red-500' : 'text-gray-500' }}">
                                        {{ $reservation->days_remaining }} día(s)
                                    </span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded
                                    bg-{{ $reservation->status_color }}-100 dark:bg-{{ $reservation->status_color }}-900/30
                                    text-{{ $reservation->status_color }}-800 dark:text-{{ $reservation->status_color }}-400">
                                    {{ $reservation->status_label }}
                                </span>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Ver detalle --}}
                                    <a href="{{ route('admin.reservations.show', $reservation) }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition"
                                       title="Ver detalle">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    @if($reservation->status === 'active')
                                        {{-- Marcar como entregado --}}
                                        {{-- <form action="{{ route('admin.reservations.complete', $reservation) }}" method="POST"
                                              onsubmit="return confirm('¿Marcar como entregado?')">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition"
                                                    title="Marcar entregado">
                                                <i class="fa-solid fa-check-circle"></i>
                                            </button>
                                        </form> --}}

                                        <form action="{{ route('admin.reservations.complete', $reservation) }}" method="POST"
                                              class="swal-confirm-form"
                                              data-swal-title="¿Marcar como entregado?"
                                              data-swal-text="Confirma que el cliente ha recogido y pagado los productos de este apartado."
                                              data-swal-icon="question"
                                              data-swal-confirm="Sí, entregado"
                                              data-swal-cancel="No, aún no"
                                              data-swal-color="#16a34a">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition"
                                                    title="Marcar entregado">
                                                <i class="fa-solid fa-check-circle"></i>
                                            </button>
                                        </form>

                                        {{-- Cancelar --}}
                                        {{-- <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST"
                                              onsubmit="return confirm('¿Cancelar este apartado? El stock será restaurado.')">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition"
                                                    title="Cancelar apartado">
                                                <i class="fa-solid fa-times-circle"></i>
                                            </button>
                                        </form> --}}

                                        <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST"
                                              class="swal-confirm-form"
                                              data-swal-title="¿Cancelar este apartado?"
                                              data-swal-text="El stock de los productos será restaurado automáticamente."
                                              data-swal-icon="warning"
                                              data-swal-confirm="Sí, cancelar"
                                              data-swal-cancel="No, mantener">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition"
                                                    title="Cancelar apartado">
                                                <i class="fa-solid fa-times-circle"></i>
                                            </button>
                                        </form>

                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            {{ $reservations->links() }}
        </div>
    @endif
</div>

{{-- SweetAlert2: Confirmación elegante --}}
@include('layouts.partials.swal-confirm')
</x-admin-layout>
