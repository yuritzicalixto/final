{{-- resources/views/client/appointments/index.blade.php --}}

<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Mis Citas',
    ],
]">

<div class="max-w-4xl mx-auto py-8 px-4">

    {{-- Encabezado --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mis Citas</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Historial y estado de todas tus citas agendadas.</p>
        </div>
        <a href="{{ route('client.appointments.create') }}"
           class="mt-4 sm:mt-0 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <i class="fa-solid fa-plus text-xs"></i>
            Nueva Cita
        </a>
    </div>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <i class="fa-solid fa-circle-check text-green-600 dark:text-green-400"></i>
            <span class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Listado de citas --}}
    @forelse($appointments as $appointment)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-4 overflow-hidden hover:shadow-md transition-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-5">

                {{-- Indicador de estado (barra lateral) --}}
                <div class="hidden sm:block w-1 self-stretch rounded-full" style="background-color: {{ $appointment->status_color }};"></div>

                {{-- Fecha visual --}}
                <div class="flex-shrink-0 text-center w-16">
                    <p class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500">
                        {{ $appointment->date->translatedFormat('M') }}
                    </p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">
                        {{ $appointment->date->format('d') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">
                        {{ $appointment->date->translatedFormat('D') }}
                    </p>
                </div>

                {{-- Info principal --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white truncate">
                        {{ $appointment->service->name }}
                    </h3>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-sm text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-clock text-xs"></i>
                            {{ $appointment->time_range }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-user-scissors text-xs"></i>
                            {{ $appointment->stylist->user->name ?? 'Por asignar' }}
                        </span>
                    </div>
                </div>

                {{-- Badge de estado --}}
                <div class="flex-shrink-0">
                    @php
                        $statusLabels = [
                            'pending'   => 'Pendiente',
                            'confirmed' => 'Confirmada',
                            'completed' => 'Completada',
                            'cancelled' => 'Cancelada',
                            'no_show'   => 'No asistió',
                        ];
                        $statusIcons = [
                            'pending'   => 'fa-solid fa-hourglass-half',
                            'confirmed' => 'fa-solid fa-circle-check',
                            'completed' => 'fa-solid fa-check-double',
                            'cancelled' => 'fa-solid fa-ban',
                            'no_show'   => 'fa-solid fa-user-xmark',
                        ];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full"
                          style="background: {{ $appointment->status_color }}15; color: {{ $appointment->status_color }};">
                        <i class="{{ $statusIcons[$appointment->status] ?? 'fa-solid fa-circle' }} text-[10px]"></i>
                        {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                    </span>
                </div>
            </div>
        </div>
    @empty
        {{-- Estado vacío --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col items-center justify-center py-16 px-6">
                <div class="w-16 h-16 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-regular fa-calendar text-2xl text-indigo-500 dark:text-indigo-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Sin citas agendadas</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Aún no tienes citas. ¡Agenda tu primera cita ahora!</p>
                <a href="{{ route('client.appointments.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Agendar mi primera cita
                </a>
            </div>
        </div>
    @endforelse
</div>

</x-admin-layout>
