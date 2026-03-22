{{-- resources/views/stylist/appointments/index.blade.php --}}

<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Mis Citas',
    ],
]">

<div class="max-w-5xl mx-auto py-8 px-4">

    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mis Citas</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Revisa tu agenda y las citas que tienes programadas.</p>
    </div>

    {{-- Estadísticas rápidas --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        {{-- Total --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wide">Total</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['total'] }}</p>
        </div>
        {{-- Pendientes --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-xs font-semibold text-amber-500 uppercase tracking-wide">Pendientes</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['pending'] }}</p>
        </div>
        {{-- Confirmadas --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-xs font-semibold text-blue-500 uppercase tracking-wide">Confirmadas</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['confirmed'] }}</p>
        </div>
        {{-- Completadas --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-xs font-semibold text-green-500 uppercase tracking-wide">Completadas</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['completed'] }}</p>
        </div>
    </div>

    {{-- Controles de navegación: vista + período --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 gap-4">

            {{-- Selector de vista (Día / Semana / Mes) --}}
            <div class="inline-flex rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                @php
                    $views = [
                        'day'   => 'Día',
                        'week'  => 'Semana',
                        'month' => 'Mes',
                    ];
                @endphp
                @foreach($views as $key => $label)
                    <a href="{{ route('stylist.appointments.index', ['view' => $key, 'date' => $referenceDate->format('Y-m-d')]) }}"
                       class="px-4 py-2 text-xs font-medium transition-colors
                              {{ $currentView === $key
                                  ? 'bg-indigo-600 text-white'
                                  : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Navegación de período (← período →) --}}
            <div class="flex items-center gap-3">
                @php
                    // Calcular fecha anterior y siguiente según la vista
                    $prevDate = match($currentView) {
                        'day'   => $referenceDate->copy()->subDay(),
                        'month' => $referenceDate->copy()->subMonth(),
                        default => $referenceDate->copy()->subWeek(),
                    };
                    $nextDate = match($currentView) {
                        'day'   => $referenceDate->copy()->addDay(),
                        'month' => $referenceDate->copy()->addMonth(),
                        default => $referenceDate->copy()->addWeek(),
                    };
                @endphp

                <a href="{{ route('stylist.appointments.index', ['view' => $currentView, 'date' => $prevDate->format('Y-m-d')]) }}"
                   class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>

                <span class="text-sm font-semibold text-gray-800 dark:text-white min-w-[200px] text-center">
                    {{ $periodLabel }}
                </span>

                <a href="{{ route('stylist.appointments.index', ['view' => $currentView, 'date' => $nextDate->format('Y-m-d')]) }}"
                   class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>

                {{-- Botón "Hoy" --}}
                <a href="{{ route('stylist.appointments.index', ['view' => $currentView, 'date' => today()->format('Y-m-d')]) }}"
                   class="px-3 py-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                    Hoy
                </a>
            </div>
        </div>
    </div>

    {{-- ======================================= --}}
    {{-- CONTENIDO SEGÚN LA VISTA                --}}
    {{-- ======================================= --}}

    @if($appointments->isEmpty())
        {{-- Estado vacío --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col items-center justify-center py-16 px-6">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-regular fa-calendar-check text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-1">Sin citas en este período</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">No tienes citas programadas para {{ mb_strtolower($views[$currentView]) }}.</p>
            </div>
        </div>

    @elseif($currentView === 'day')
        {{-- ============================= --}}
        {{-- VISTA DÍA — Lista cronológica --}}
        {{-- ============================= --}}
        <div class="space-y-3">
            @foreach($appointments as $appointment)
                @include('stylist.appointments._appointment-card', ['appointment' => $appointment])
            @endforeach
        </div>

    @elseif($currentView === 'week')
        {{-- ============================= --}}
        {{-- VISTA SEMANA — Agrupada por día --}}
        {{-- ============================= --}}
        <div class="space-y-6">
            @foreach($weekDays as $day)
                @php
                    $dayKey = $day->format('Y-m-d');
                    $dayAppointments = $appointmentsByDate->get($dayKey, collect());
                    $isToday = $day->isToday();
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden {{ $isToday ? 'ring-2 ring-indigo-500/30' : '' }}">
                    {{-- Cabecera del día --}}
                    <div class="flex items-center justify-between px-5 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            @if($isToday)
                                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                            @endif
                            <span class="text-sm font-semibold text-gray-800 dark:text-white">
                                {{ $day->translatedFormat('l d') }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $dayAppointments->count() }} {{ $dayAppointments->count() === 1 ? 'cita' : 'citas' }}
                        </span>
                    </div>

                    {{-- Citas del día --}}
                    @if($dayAppointments->isEmpty())
                        <div class="px-5 py-4 text-sm text-gray-400 dark:text-gray-500 text-center">
                            Sin citas
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($dayAppointments as $appointment)
                                <a href="{{ route('stylist.appointments.index', ['view' => 'day', 'date' => $appointment->date->format('Y-m-d')]) }}"
   class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors cursor-pointer">
                                    {{-- Hora --}}
                                    <div class="flex-shrink-0 w-20 text-right">
                                        <p class="text-sm font-mono font-semibold text-gray-800 dark:text-white">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                        </p>
                                    </div>

                                    {{-- Barra de color --}}
                                    <div class="w-1 self-stretch rounded-full" style="background-color: {{ $appointment->status_color }};"></div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                            {{ $appointment->service->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $appointment->client->name }} · {{ $appointment->time_range }}
                                        </p>
                                    </div>

                                    {{-- Estado --}}
                                    @php
                                        $statusLabels = [
                                            'pending'   => 'Pendiente',
                                            'confirmed' => 'Confirmada',
                                            'completed' => 'Completada',
                                            'cancelled' => 'Cancelada',
                                            'no_show'   => 'No asistió',
                                        ];
                                    @endphp
                                    <span class="flex-shrink-0 px-2.5 py-1 text-xs font-medium rounded-full"
                                          style="background: {{ $appointment->status_color }}15; color: {{ $appointment->status_color }};">
                                        {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    @else
        {{-- ============================= --}}
        {{-- VISTA MES — Lista completa    --}}
        {{-- ============================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @php $lastDate = ''; @endphp
                @foreach($appointments as $appointment)
                    @php $currentDate = $appointment->date->format('Y-m-d'); @endphp

                    {{-- Separador de fecha cuando cambia el día --}}
                    @if($currentDate !== $lastDate)
                        <div class="px-5 py-2 bg-gray-50 dark:bg-gray-700/50">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                {{ $appointment->date->translatedFormat('l, d \d\e F') }}
                                @if($appointment->date->isToday())
                                    <span class="ml-2 text-indigo-600 dark:text-indigo-400">(Hoy)</span>
                                @endif
                            </p>
                        </div>
                        @php $lastDate = $currentDate; @endphp
                    @endif

                    <div class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                        <div class="flex-shrink-0 w-20 text-right">
                            <p class="text-sm font-mono font-semibold text-gray-800 dark:text-white">
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                            </p>
                        </div>
                        <div class="w-1 self-stretch rounded-full" style="background-color: {{ $appointment->status_color }};"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                {{ $appointment->service->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $appointment->client->name }} · {{ $appointment->time_range }}
                            </p>
                        </div>
                        @php
                            $statusLabels = [
                                'pending'   => 'Pendiente',
                                'confirmed' => 'Confirmada',
                                'completed' => 'Completada',
                                'cancelled' => 'Cancelada',
                                'no_show'   => 'No asistió',
                            ];
                        @endphp
                        <span class="flex-shrink-0 px-2.5 py-1 text-xs font-medium rounded-full"
                              style="background: {{ $appointment->status_color }}15; color: {{ $appointment->status_color }};">
                            {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

</x-admin-layout>
