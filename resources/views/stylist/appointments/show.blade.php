<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Mis Citas',
        'route' => route('stylist.appointments.index'),
    ],
    [
        'name'=> $appointment->service->name,
    ],
]">

<div class="max-w-3xl mx-auto">

    {{-- Encabezado con acciones --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalle de Cita</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Cita #{{ $appointment->id }} — {{ $appointment->date->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-full"
                  style="background: {{ $appointment->status_color }}15; color: {{ $appointment->status_color }};">
                <i class="{{ $appointment->status_icon }}"></i>
                {{ $appointment->status_label }}
            </span>
        </div>
    </div>

    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <i class="fa-solid fa-circle-check text-green-600 dark:text-green-400"></i>
            <span class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Contenido principal --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

        {{-- ── Información del Servicio ── --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">Servicio</h3>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $appointment->service->name }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $appointment->service->description }}</p>
            <div class="flex flex-wrap items-center gap-4 mt-3">
                <span class="inline-flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-300">
                    <i class="fa-regular fa-clock text-xs text-gray-400"></i>
                    {{ $appointment->service->duration_formatted }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                    <i class="fa-solid fa-tag text-xs"></i>
                    {{ $appointment->service->price_formatted }}
                </span>
                @if($appointment->service->category)
                    <span class="inline-flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-300">
                        <i class="fa-solid fa-folder text-xs text-gray-400"></i>
                        {{ $appointment->service->category }}
                    </span>
                @endif
            </div>
        </div>

        {{-- ── Información de la Cita ── --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Detalles de la Cita</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Fecha</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white capitalize">
                        {{ $appointment->date->locale('es')->isoFormat('ddd D MMM YYYY') }}
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Horario</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                        {{ $appointment->time_range }}
                    </p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Agendada</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">
                        {{ $appointment->created_at->locale('es')->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Información del Cliente ── --}}
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Cliente</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/40 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                        {{ strtoupper(substr($appointment->client->name ?? 'C', 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">{{ $appointment->client->name ?? 'Cliente' }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->client->email ?? '' }}</p>
                    @if($appointment->client->phone)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <i class="fa-solid fa-phone text-xs mr-1"></i>{{ $appointment->client->phone }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Notas del cliente ── --}}
        @if($appointment->notes)
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">Notas del Cliente</h3>
                <div class="p-3 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-lg">
                    <p class="text-sm text-amber-700 dark:text-amber-300">{{ $appointment->notes }}</p>
                </div>
            </div>
        @endif

        {{-- ── Acciones ── --}}
        @if(in_array($appointment->status, ['pending', 'confirmed']))
            <div class="p-6 bg-gray-50 dark:bg-gray-700/30">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-4">Acciones</h3>
                <div class="flex flex-wrap gap-3">

                    @if($appointment->status === 'pending')
                        <form action="{{ route('stylist.appointments.updateStatus', $appointment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                                Confirmar Cita
                            </button>
                        </form>
                    @endif

                    @if($appointment->status === 'confirmed')
                        <form action="{{ route('stylist.appointments.updateStatus', $appointment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm">
                                <i class="fa-solid fa-check-double text-xs"></i>
                                Marcar Completada
                            </button>
                        </form>
                        <form action="{{ route('stylist.appointments.updateStatus', $appointment) }}" method="POST"
                              onsubmit="return confirm('¿Marcar como no asistió?')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="no_show">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-gray-800 border border-red-300 dark:border-red-800 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <i class="fa-solid fa-user-slash text-xs"></i>
                                No Asistió
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Botón volver --}}
    <div class="mt-6">
        <a href="{{ route('stylist.appointments.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Volver a mis citas
        </a>
    </div>
</div>

</x-admin-layout>
