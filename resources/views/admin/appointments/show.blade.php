<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Citas',
        'route' => route('admin.appointments.index'),
    ],
    [
        'name'=> 'Detalle #' . $appointment->id,
    ]
]">

    @php
        // Configuración visual por estado (centralizada para toda la vista)
        $statusConfig = match($appointment->status) {
            'pending'   => ['label' => 'Pendiente',  'classes' => 'bg-amber-100 text-amber-700 ring-amber-600/20',  'icon' => 'fa-clock'],
            'confirmed' => ['label' => 'Confirmada', 'classes' => 'bg-blue-100 text-blue-700 ring-blue-600/20',    'icon' => 'fa-circle-check'],
            'completed' => ['label' => 'Completada', 'classes' => 'bg-green-100 text-green-700 ring-green-600/20',  'icon' => 'fa-check-double'],
            'cancelled' => ['label' => 'Cancelada',  'classes' => 'bg-red-100 text-red-700 ring-red-600/20',       'icon' => 'fa-xmark'],
            'no_show'   => ['label' => 'No asistió', 'classes' => 'bg-gray-100 text-gray-600 ring-gray-500/20',    'icon' => 'fa-user-slash'],
            default     => ['label' => $appointment->status, 'classes' => 'bg-gray-100 text-gray-600 ring-gray-500/20', 'icon' => 'fa-question'],
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══════════════════════════════════════
             Columna principal: Información de la cita
             ═══════════════════════════════════════ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Tarjeta de encabezado --}}
            <div class="card">
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Cita #{{ $appointment->id }}</h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Creada el {{ $appointment->created_at->translatedFormat('d \d\e F Y, H:i') }}
                        </p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium ring-1 ring-inset {{ $statusConfig['classes'] }}">
                        <i class="fa-solid {{ $statusConfig['icon'] }} mr-1.5 text-xs"></i>
                        {{ $statusConfig['label'] }}
                    </span>
                </div>

                {{-- Datos principales en grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Fecha --}}
                    <div>
                        <span class="block text-sm font-medium text-gray-400 mb-1">Fecha</span>
                        <span class="text-gray-900">
                            <i class="fa-regular fa-calendar mr-1.5 text-gray-400 text-sm"></i>
                            {{ $appointment->date->translatedFormat('l, d \d\e F Y') }}
                        </span>
                    </div>

                    {{-- Horario --}}
                    <div>
                        <span class="block text-sm font-medium text-gray-400 mb-1">Horario</span>
                        <span class="text-gray-900">
                            <i class="fa-regular fa-clock mr-1.5 text-gray-400 text-sm"></i>
                            {{ $appointment->time_range }}
                        </span>
                    </div>

                    {{-- Servicio --}}
                    <div>
                        <span class="block text-sm font-medium text-gray-400 mb-1">Servicio</span>
                        <span class="text-gray-900">
                            <i class="fa-solid fa-scissors mr-1.5 text-gray-400 text-sm"></i>
                            {{ $appointment->service->name ?? '—' }}
                        </span>
                        @if($appointment->service)
                            <span class="block text-xs text-gray-500 mt-0.5 ml-5">
                                {{ $appointment->service->duration_formatted }} · {{ $appointment->service->price_formatted }}
                            </span>
                        @endif
                    </div>

                    {{-- Estilista --}}
                    <div>
                        <span class="block text-sm font-medium text-gray-400 mb-1">Estilista</span>
                        <span class="text-gray-900">
                            <i class="fa-solid fa-user-gear mr-1.5 text-gray-400 text-sm"></i>
                            {{ $appointment->stylist?->user?->name ?? 'Sin asignar' }}
                        </span>
                    </div>
                </div>

                {{-- Notas --}}
                @if($appointment->notes)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <span class="block text-sm font-medium text-gray-400 mb-1">Notas</span>
                        <p class="text-gray-700 text-sm">{{ $appointment->notes }}</p>
                    </div>
                @endif

                {{-- Info de cancelación --}}
                @if($appointment->status === 'cancelled')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="rounded-lg bg-red-50 border border-red-200 p-4">
                            <h4 class="text-sm font-medium text-red-800 mb-2">
                                <i class="fa-solid fa-triangle-exclamation mr-1.5"></i>
                                Información de cancelación
                            </h4>
                            <div class="text-sm text-red-700 space-y-1">
                                <p><span class="font-medium">Motivo:</span> {{ $appointment->cancellation_reason ?? 'No especificado' }}</p>
                                <p><span class="font-medium">Cancelada por:</span> {{ $appointment->cancelled_by === 'admin' ? 'Administrador' : 'Cliente' }}</p>
                                @if($appointment->cancelled_at)
                                    <p><span class="font-medium">Fecha:</span> {{ $appointment->cancelled_at->translatedFormat('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════
             Columna lateral: Cliente y Acciones
             ═══════════════════════════════════════ --}}
        <div class="space-y-6">

            {{-- Tarjeta del cliente --}}
            <div class="card">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Cliente</h3>

                <div class="flex items-center space-x-3 mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center">
                        <span class="text-sm font-bold text-sky-600">
                            {{ strtoupper(substr($appointment->client->name ?? '?', 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $appointment->client->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500">{{ $appointment->client->email ?? '' }}</p>
                    </div>
                </div>

                @if($appointment->client?->phone)
                    <div class="text-sm text-gray-600">
                        <i class="fa-solid fa-phone mr-1.5 text-gray-400 text-xs"></i>
                        {{ $appointment->client->phone }}
                    </div>
                @endif
            </div>

            {{-- Tarjeta de acciones (solo si la cita se puede modificar) --}}
            @if(in_array($appointment->status, ['pending', 'confirmed']))
                <div class="card">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Acciones</h3>

                    <div class="space-y-2">
                        {{-- Confirmar (solo si está pendiente) --}}
                        @if($appointment->status === 'pending')
                            <form action="{{ route('admin.appointments.confirm', $appointment) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i class="fa-solid fa-circle-check mr-2 text-xs"></i>
                                    Confirmar cita
                                </button>
                            </form>
                        @endif

                        {{-- Completar (solo si está confirmada) --}}
                        @if($appointment->status === 'confirmed')
                            <form action="{{ route('admin.appointments.complete', $appointment) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                                    <i class="fa-solid fa-check-double mr-2 text-xs"></i>
                                    Marcar completada
                                </button>
                            </form>
                        @endif

                        {{-- No asistió --}}
                        <button onclick="confirmNoShow()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fa-solid fa-user-slash mr-2 text-xs"></i>
                            No asistió
                        </button>

                        {{-- Cancelar --}}
                        <button onclick="confirmCancel()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 transition-colors">
                            <i class="fa-solid fa-ban mr-2 text-xs"></i>
                            Cancelar cita
                        </button>
                    </div>
                </div>
            @endif

            {{-- Botón de volver --}}
            <a href="{{ route('admin.appointments.index') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors w-full justify-center">
                <i class="fa-solid fa-arrow-left mr-2 text-xs"></i>
                Volver al listado
            </a>
        </div>
    </div>

    {{-- Formularios ocultos para acciones destructivas --}}
    <form action="{{ route('admin.appointments.noshow', $appointment) }}" method="POST" id="noShowForm">
        @csrf
    </form>

    <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" id="cancelForm">
        @csrf
        <input type="hidden" name="reason" id="cancelReason">
    </form>

    @push('js')
        <script>
            function confirmNoShow() {
                Swal.fire({
                    title: '¿Marcar como no asistió?',
                    text: 'Se registrará que el cliente no se presentó a la cita.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6B7280',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, marcar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('noShowForm').submit();
                    }
                });
            }

            function confirmCancel() {
                Swal.fire({
                    title: '¿Cancelar esta cita?',
                    input: 'textarea',
                    inputLabel: 'Motivo de cancelación',
                    inputPlaceholder: 'Escribe el motivo (opcional)...',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Sí, cancelar cita',
                    cancelButtonText: 'Volver',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('cancelReason').value = result.value || 'Cancelada por administrador';
                        document.getElementById('cancelForm').submit();
                    }
                });
            }
        </script>
    @endpush

</x-admin-layout>
