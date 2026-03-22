{{-- resources/views/stylist/appointments/_appointment-card.blade.php --}}
{{-- Partial reutilizable para mostrar una cita en la vista de día --}}

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

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
    <div class="flex items-stretch">
        {{-- Barra lateral de color --}}
        <div class="w-1.5 flex-shrink-0" style="background-color: {{ $appointment->status_color }};"></div>

        <div class="flex-1 p-5">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                {{-- Bloque de hora --}}
                <div class="flex-shrink-0 text-center sm:text-right w-20">
                    <p class="text-lg font-bold text-gray-800 dark:text-white font-mono">
                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 uppercase">
                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('A') }}
                    </p>
                </div>

                {{-- Info del servicio y cliente --}}
                <div class="flex-1 min-w-0">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white truncate">
                        {{ $appointment->service->name }}
                    </h3>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-sm text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-user text-xs"></i>
                            {{ $appointment->client->name }}
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i class="fa-regular fa-clock text-xs"></i>
                            {{ $appointment->time_range }}
                        </span>
                    </div>
                    @if($appointment->notes)
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500 italic truncate">
                            <i class="fa-regular fa-note-sticky mr-1"></i>
                            {{ $appointment->notes }}
                        </p>
                    @endif
                </div>

                {{-- Estado --}}
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full"
                          style="background: {{ $appointment->status_color }}15; color: {{ $appointment->status_color }};">
                        <i class="{{ $statusIcons[$appointment->status] ?? 'fa-solid fa-circle' }} text-[10px]"></i>
                        {{ $statusLabels[$appointment->status] ?? ucfirst($appointment->status) }}
                    </span>
                </div>
            </div>

            {{-- ============================= --}}
            {{-- BOTONES DE ACCIÓN             --}}
            {{-- ============================= --}}
            @if(in_array($appointment->status, ['pending', 'confirmed']))
                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">

                    {{-- Botón CONFIRMAR — solo si está pendiente --}}
                    @if($appointment->status === 'pending')
                        <form id="confirm-form-{{ $appointment->id }}"
                              action="{{ route('stylist.appointments.confirm', $appointment) }}" method="POST" class="inline">
                            @csrf
                            <button type="button"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors"
                                    onclick="Swal.fire({
                                        title: '¿Confirmar esta cita?',
                                        text: 'El cliente recibirá una notificación de confirmación.',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#2563eb',
                                        cancelButtonColor: '#6b7280',
                                        confirmButtonText: 'Sí, confirmar',
                                        cancelButtonText: 'Cancelar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('confirm-form-{{ $appointment->id }}').submit();
                                        }
                                    })">
                                <i class="fa-solid fa-circle-check"></i>
                                Confirmar
                            </button>
                        </form>
                    @endif

                    {{-- Botón COMPLETAR — solo si está confirmada --}}
                    @if($appointment->status === 'confirmed')
                        <form id="complete-form-{{ $appointment->id }}"
                              action="{{ route('stylist.appointments.complete', $appointment) }}" method="POST" class="inline">
                            @csrf
                            <button type="button"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors"
                                    onclick="Swal.fire({
                                        title: '¿Marcar como completada?',
                                        text: 'La cita se registrará como terminada.',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#16a34a',
                                        cancelButtonColor: '#6b7280',
                                        confirmButtonText: 'Sí, completar',
                                        cancelButtonText: 'Cancelar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('complete-form-{{ $appointment->id }}').submit();
                                        }
                                    })">
                                <i class="fa-solid fa-check-double"></i>
                                Completar
                            </button>
                        </form>
                    @endif

                    {{-- Botón NO ASISTIÓ — si está pendiente o confirmada --}}
                    <form id="noshow-form-{{ $appointment->id }}"
                          action="{{ route('stylist.appointments.noShow', $appointment) }}" method="POST" class="inline">
                        @csrf
                        <button type="button"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                onclick="Swal.fire({
                                    title: '¿El cliente no asistió?',
                                    text: 'La cita se marcará como inasistencia.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d97706',
                                    cancelButtonColor: '#6b7280',
                                    confirmButtonText: 'Sí, no asistió',
                                    cancelButtonText: 'Cancelar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('noshow-form-{{ $appointment->id }}').submit();
                                    }
                                })">
                            <i class="fa-solid fa-user-xmark"></i>
                            No asistió
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
