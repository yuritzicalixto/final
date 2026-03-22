<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Citas',
    ],
]">

    {{-- ═══════════════════════════════════════════════
         Tarjetas de estadísticas generales
         ═══════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Citas hoy --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Hoy</span>
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50">
                    <i class="fa-solid fa-calendar-day text-blue-500 text-sm"></i>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['today_total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $stats['today_pending'] }} por atender
            </p>
        </div>

        {{-- Citas esta semana --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Semana</span>
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50">
                    <i class="fa-solid fa-calendar-week text-purple-500 text-sm"></i>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['week_count'] }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $stats['pending_total'] }} pendientes en total
            </p>
        </div>

        {{-- Completadas este mes --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Completadas</span>
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50">
                    <i class="fa-solid fa-circle-check text-green-500 text-sm"></i>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['month_completed'] }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $stats['month_cancelled'] }} canceladas este mes
            </p>
        </div>

        {{-- Tasa de asistencia --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Asistencia</span>
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50">
                    <i class="fa-solid fa-chart-simple text-amber-500 text-sm"></i>
                </span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['attendance_rate'] }}%</p>
            <p class="text-xs text-gray-500 mt-1">
                del mes actual
            </p>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════════
         Tabla filtrable de citas (componente Livewire)
         ═══════════════════════════════════════════════ --}}
    @livewire('admin-appointment-table')

</x-admin-layout>
