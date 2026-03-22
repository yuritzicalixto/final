<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Mis Apartados',
    ],
]">

{{-- Mensajes flash --}}
@if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
        <p class="text-green-800 dark:text-green-300">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </p>
    </div>
@endif
@if(session('error'))
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
        <p class="text-red-800 dark:text-red-300">
            <i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}
        </p>
    </div>
@endif

{{-- ═══════════════════════════════════════════
     APARTADOS ACTIVOS
     ═══════════════════════════════════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fa-solid fa-box-archive text-yellow-500 mr-3"></i>
            Apartados Activos
        </h2>
    </div>

    @if($activeReservations->isEmpty())
        <div class="p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                <i class="fa-solid fa-box-open text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No tienes apartados activos</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Explora nuestros productos y crea tu primer apartado</p>
            <a href="{{ route('sitio.productos') }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-medium rounded-lg transition">
                <i class="fa-solid fa-box mr-2"></i> Ver Productos
            </a>
        </div>
    @else
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($activeReservations as $reservation)
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            {{-- Header --}}
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 font-mono font-semibold rounded">
                                    {{ $reservation->reservation_number }}
                                </span>
                                @if($reservation->is_expiring)
                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-semibold rounded animate-pulse">
                                        <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                        ¡Vence pronto!
                                    </span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Fecha de apartado</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $reservation->reservation_date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Fecha límite</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $reservation->expiration_date->format('d/m/Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Productos</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $reservation->items->count() }} items</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400">Total</p>
                                    <p class="font-bold text-yellow-600 dark:text-yellow-400 text-lg">{{ $reservation->total_formatted }}</p>
                                </div>
                            </div>

                            {{-- Preview de productos --}}
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($reservation->items->take(3) as $item)
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs rounded">
                                        {{ $item->product->name }} (x{{ $item->quantity }})
                                    </span>
                                @endforeach
                                @if($reservation->items->count() > 3)
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-500 text-xs rounded">
                                        +{{ $reservation->items->count() - 3 }} más
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Días restantes y acciones --}}
                        <div class="flex flex-col items-end gap-3">
                            <div class="text-center">
                                <div class="text-3xl font-bold {{ $reservation->is_expiring ? 'text-red-500' : 'text-green-500' }}">
                                    {{ $reservation->days_remaining }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">días restantes</div>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('client.reservations.show', $reservation) }}"
                                   class="px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 rounded-lg transition">
                                    Ver detalle
                                </a>
                                {{-- <form action="{{ route('client.reservations.cancel', $reservation) }}" method="POST"
                                      onsubmit="return confirm('¿Cancelar este apartado? Los productos regresarán al inventario.')">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 rounded-lg transition">
                                        Cancelar
                                    </button>
                                </form> --}}

                                <form action="{{ route('client.reservations.cancel', $reservation) }}" method="POST"
                                      class="swal-confirm-form"
                                      data-swal-title="¿Cancelar apartado?"
                                      data-swal-text="Los productos regresarán al inventario y esta acción no se puede deshacer."
                                      data-swal-icon="warning"
                                      data-swal-confirm="Sí, cancelar"
                                      data-swal-cancel="No, mantener">
                                    @csrf
                                    <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 rounded-lg transition">
                                        Cancelar
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════
     HISTORIAL DE APARTADOS
     ═══════════════════════════════════════════ --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
            <i class="fa-solid fa-history text-gray-500 mr-3"></i>
            Historial de Apartados
        </h2>
    </div>

    @if($historyReservations->isEmpty())
        <div class="p-12 text-center text-gray-500 dark:text-gray-400">
            <i class="fa-solid fa-inbox text-4xl mb-4"></i>
            <p>No tienes apartados anteriores.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">No. Apartado</th>
                        <th class="px-6 py-3">Fecha</th>
                        <th class="px-6 py-3">Productos</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($historyReservations as $reservation)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 font-mono font-medium text-gray-900 dark:text-white">
                                {{ $reservation->reservation_number }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $reservation->reservation_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $reservation->items->count() }} productos
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $reservation->total_formatted }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded
                                    bg-{{ $reservation->status_color }}-100 dark:bg-{{ $reservation->status_color }}-900
                                    text-{{ $reservation->status_color }}-800 dark:text-{{ $reservation->status_color }}-200">
                                    {{ $reservation->status_label }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            {{ $historyReservations->links() }}
        </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════
     SweetAlert2: Confirmación elegante para formularios
     ═══════════════════════════════════════════════════ --}}
@include('layouts.partials.swal-confirm')

</x-admin-layout>
