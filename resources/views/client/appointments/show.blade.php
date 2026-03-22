{{-- resources/views/client/appointments/show.blade.php --}}

<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Mis Citas',
        'route' => route('client.appointments.index'),
    ],
    [
        'name'=> 'Detalle de Cita',
    ],
]">

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

<div class="max-w-2xl mx-auto py-8 px-4">

    {{-- Encabezado con estado --}}
    <div class="mb-8 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalle de Cita</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Información completa de tu cita agendada.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium rounded-full"
              style="background: {{ $appointment->status_color }}15; color: {{ $appointment->status_color }};">
            <i class="{{ $statusIcons[$appointment->status] ?? 'fa-solid fa-circle' }}"></i>
            {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
        </span>
    </div>

    {{-- Card principal --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

        {{-- Servicio --}}
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wide mb-1">Servicio</p>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">{{ $appointment->service->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $appointment->service->description }}</p>
        </div>

        {{-- Detalles --}}
        <div class="p-6 space-y-4">
            {{-- Fecha y Hora --}}
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-regular fa-calendar text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase">Fecha y Hora</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white mt-0.5">
                        {{ $appointment->date->translatedFormat('l, d \d\e F \d\e Y') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $appointment->time_range }}
                    </p>
                </div>
            </div>

            {{-- Estilista --}}
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-user-scissors text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase">Estilista</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white mt-0.5">
                        {{ $appointment->stylist->user->name ?? 'Por asignar' }}
                    </p>
                    @if($appointment->stylist && $appointment->stylist->specialties)
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $appointment->stylist->specialties }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Precio --}}
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-tag text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase">Precio</p>
                    <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5">
                        {{ $appointment->service->price_formatted }}
                    </p>
                </div>
            </div>

            {{-- Notas --}}
            @if($appointment->notes)
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                        <i class="fa-regular fa-note-sticky text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase">Notas</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5">
                            {{ $appointment->notes }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- Motivo de cancelación --}}
            @if($appointment->status === 'cancelled' && $appointment->cancellation_reason)
                <div class="flex items-start gap-4 mt-4 p-4 bg-red-50 dark:bg-red-900/10 rounded-lg">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-ban text-red-500"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-red-500 uppercase">Motivo de cancelación</p>
                        <p class="text-sm text-red-700 dark:text-red-300 mt-0.5">
                            {{ $appointment->cancellation_reason }}
                        </p>
                        <p class="text-xs text-red-400 mt-1">
                            Cancelada por: {{ $appointment->cancelled_by === 'client' ? 'Ti' : 'Administrador' }}
                            · {{ $appointment->cancelled_at?->translatedFormat('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Botón regresar --}}
    <div class="mt-6">
        <a href="{{ route('client.appointments.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Volver a mis citas
        </a>
    </div>
</div>

</x-admin-layout>
