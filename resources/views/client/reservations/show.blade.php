<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Mis Apartados',
        'route' => route('client.reservations.index'),
    ],
    [
        'name'=> $reservation->reservation_number,
    ],
]">

<div class="max-w-3xl mx-auto py-8 px-4">

    {{-- Mensaje de éxito al crear --}}
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check text-green-500 text-xl mr-3"></i>
                <p class="text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Encabezado del apartado --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Apartado {{ $reservation->reservation_number }}
                </h1>
                <span class="inline-block mt-1 px-3 py-1 text-sm font-semibold rounded
                    bg-{{ $reservation->status_color }}-100 dark:bg-{{ $reservation->status_color }}-900/30
                    text-{{ $reservation->status_color }}-800 dark:text-{{ $reservation->status_color }}-400">
                    {{ $reservation->status_label }}
                </span>
            </div>

            @if($reservation->status === 'active')
                <div class="text-center">
                    <div class="text-3xl font-bold {{ $reservation->is_expiring ? 'text-red-500' : 'text-green-500' }}">
                        {{ $reservation->days_remaining }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">días restantes</div>
                </div>
            @endif
        </div>

        {{-- Info del apartado --}}
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
                <p class="font-bold text-lg text-yellow-600 dark:text-yellow-400">{{ $reservation->total_formatted }}</p>
            </div>
        </div>

        {{-- Fecha de recolección preferida --}}
        @if($reservation->preferred_pickup_date)
            <div class="mt-4 p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                <p class="text-sm text-indigo-800 dark:text-indigo-300">
                    <i class="fa-solid fa-calendar-check mr-1"></i>
                    <strong>Fecha preferida de recolección:</strong>
                    {{ $reservation->preferred_pickup_date->translatedFormat('l d \d\e F, Y \a \l\a\s H:i') }}
                </p>
            </div>
        @endif
    </div>

    {{-- Horario de recolección --}}
    @if($reservation->status === 'active')
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-amber-800 dark:text-amber-300 mb-2">
                <i class="fa-solid fa-clock mr-1"></i> Horario para recoger
            </h3>
            <p class="text-sm text-amber-700 dark:text-amber-400 mb-3">
                Puedes pasar al salón de <strong>Lunes a Sábado de 10:00 AM a 5:00 PM</strong>.
                Tienes hasta el <strong>{{ $reservation->expiration_date->format('d/m/Y') }}</strong>.
            </p>

            @if(count($availablePickupDays) > 0)
                <p class="text-sm text-amber-700 dark:text-amber-400 mb-2">Días disponibles:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($availablePickupDays as $day)
                        <span class="px-3 py-1 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs rounded-full border border-amber-200 dark:border-amber-700">
                            {{ $day->translatedFormat('l d/m') }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- Productos del apartado --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Productos apartados</h2>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($reservation->items as $item)
                <div class="p-4 flex items-center gap-4">
                    <img src="{{ $item->product->image_url }}"
                         alt="{{ $item->product->name }}"
                         class="w-14 h-14 object-cover rounded-lg">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-800 dark:text-white">{{ $item->product->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}
                        </p>
                    </div>
                    <p class="font-semibold text-gray-800 dark:text-white">
                        {{ $item->subtotal_formatted }}
                    </p>
                </div>
            @endforeach
        </div>
        <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-between">
            <span class="font-semibold text-gray-700 dark:text-gray-200">Total</span>
            <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $reservation->total_formatted }}</span>
        </div>
    </div>

    {{-- Acciones --}}
    @if($reservation->status === 'active')
        <div class="flex gap-3 justify-end">
            <a href="{{ route('client.reservations.index') }}"
               class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                Volver a mis apartados
            </a>
            {{-- <form action="{{ route('client.reservations.cancel', $reservation) }}" method="POST"
                  onsubmit="return confirm('¿Estás seguro de cancelar este apartado? Los productos regresarán al inventario.')">
                @csrf
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg transition">
                    <i class="fa-solid fa-xmark mr-1"></i> Cancelar Apartado
                </button>
            </form> --}}

            <form action="{{ route('client.reservations.cancel', $reservation) }}" method="POST"
                  class="swal-confirm-form"
                  data-swal-title="¿Cancelar este apartado?"
                  data-swal-text="Los productos regresarán al inventario y esta acción no se puede deshacer."
                  data-swal-icon="warning"
                  data-swal-confirm="Sí, cancelar apartado"
                  data-swal-cancel="No, mantener">
                @csrf
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg transition">
                    <i class="fa-solid fa-xmark mr-1"></i> Cancelar Apartado
                </button>
            </form>

        </div>
    @else
        <div class="flex justify-end">
            <a href="{{ route('client.reservations.index') }}"
               class="px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                Volver a mis apartados
            </a>
        </div>
    @endif
</div>

{{-- ═══════════════════════════════════════════════════
     SweetAlert2: Confirmación elegante para formularios
     ═══════════════════════════════════════════════════ --}}
@include('layouts.partials.swal-confirm')
</x-admin-layout>
