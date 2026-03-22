<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Apartados',
        'route' => route('admin.reservations.index'),
    ],
    [
        'name'=> $reservation->reservation_number,
    ],
]">

<div class="max-w-4xl mx-auto py-6">

    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
            <p class="text-green-800 dark:text-green-300"><i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}</p>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $reservation->reservation_number }}
                </h1>
                <span class="inline-block mt-1 px-3 py-1 text-sm font-semibold rounded
                    bg-{{ $reservation->status_color }}-100 dark:bg-{{ $reservation->status_color }}-900/30
                    text-{{ $reservation->status_color }}-800 dark:text-{{ $reservation->status_color }}-400">
                    {{ $reservation->status_label }}
                </span>
            </div>

            {{-- Acciones --}}
            @if($reservation->status === 'active')
                <div class="flex gap-2">
                    {{-- <form action="{{ route('admin.reservations.complete', $reservation) }}" method="POST"
                          onsubmit="return confirm('¿Confirmar entrega al cliente?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                            <i class="fa-solid fa-check mr-1"></i> Marcar Entregado
                        </button>
                    </form> --}}

                    <form action="{{ route('admin.reservations.complete', $reservation) }}" method="POST"
      class="swal-confirm-form"
      data-swal-title="¿Confirmar entrega?"
      data-swal-text="Confirma que el cliente ha recogido y pagado los productos."
      data-swal-icon="question"
      data-swal-confirm="Sí, marcar entregado"
      data-swal-cancel="Cancelar"
      data-swal-color="#16a34a">
    @csrf
    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
        <i class="fa-solid fa-check mr-1"></i> Marcar Entregado
    </button>
</form>

                    {{-- <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST"
                          onsubmit="return confirm('¿Cancelar este apartado? El stock será restaurado.')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                            <i class="fa-solid fa-xmark mr-1"></i> Cancelar
                        </button>
                    </form> --}}

                    <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST"
      class="swal-confirm-form"
      data-swal-title="¿Cancelar este apartado?"
      data-swal-text="El stock de los productos será restaurado automáticamente. Esta acción no se puede deshacer."
      data-swal-icon="warning"
      data-swal-confirm="Sí, cancelar apartado"
      data-swal-cancel="No, mantener">
    @csrf
    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
        <i class="fa-solid fa-xmark mr-1"></i> Cancelar
    </button>
</form>

                </div>
            @endif
        </div>

        {{-- Información general --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-sm">
            <div>
                <p class="text-gray-500 dark:text-gray-400 mb-1">Cliente</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $reservation->client->name }}</p>
                <p class="text-gray-500 text-xs">{{ $reservation->client->email }}</p>
                @if($reservation->client->phone)
                    <p class="text-gray-500 text-xs">{{ $reservation->client->phone }}</p>
                @endif
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 mb-1">Fecha de apartado</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $reservation->reservation_date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500 dark:text-gray-400 mb-1">Fecha de vencimiento</p>
                <p class="font-medium {{ $reservation->is_expiring ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                    {{ $reservation->expiration_date->format('d/m/Y') }}
                    @if($reservation->status === 'active')
                        <span class="text-xs">({{ $reservation->days_remaining }} días)</span>
                    @endif
                </p>
            </div>
        </div>

        @if($reservation->preferred_pickup_date)
            <div class="mt-4 p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                <p class="text-sm text-indigo-800 dark:text-indigo-300">
                    <i class="fa-solid fa-calendar-check mr-1"></i>
                    <strong>Recolección preferida:</strong>
                    {{ $reservation->preferred_pickup_date->translatedFormat('l d \d\e F, Y \a \l\a\s H:i') }}
                </p>
            </div>
        @endif
    </div>

    {{-- Productos --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Productos del apartado</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-700 dark:text-gray-400 uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Producto</th>
                    <th class="px-4 py-3 text-center">Cantidad</th>
                    <th class="px-4 py-3 text-right">Precio unitario</th>
                    <th class="px-4 py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($reservation->items as $item)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}"
                                     class="w-10 h-10 object-cover rounded">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->product->brand }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">{{ $item->subtotal_formatted }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-200">Total</td>
                    <td class="px-4 py-3 text-right font-bold text-lg text-gray-900 dark:text-white">{{ $reservation->total_formatted }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <a href="{{ route('admin.reservations.index') }}"
       class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Volver al listado
    </a>
</div>
@include('layouts.partials.swal-confirm')
</x-admin-layout>
