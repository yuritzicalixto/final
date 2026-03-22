{{-- resources/views/client/appointments/create.blade.php --}}

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
        'name'=> 'Agendar Cita',
    ],
]">

<div class="max-w-3xl mx-auto py-8 px-4">

    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Agendar Cita</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Selecciona el servicio, estilista, fecha y horario disponible.</p>
    </div>

    {{-- Mensaje de error flash --}}
    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <i class="fa-solid fa-circle-exclamation text-red-600 dark:text-red-400"></i>
            <span class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Errores de validación --}}
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-circle-exclamation text-red-600 dark:text-red-400"></i>
                <span class="text-sm font-medium text-red-700 dark:text-red-300">Corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Servicio preseleccionado (info card) --}}
    @if($selectedService)
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border-l-4 border-indigo-500 p-5">
            <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase tracking-wide mb-1">Servicio seleccionado</p>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">{{ $selectedService->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $selectedService->description }}</p>
            <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center gap-1.5">
                    <i class="fa-regular fa-clock"></i>
                    {{ $selectedService->duration_formatted }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-tag"></i>
                    {{ $selectedService->price_formatted }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <i class="fa-regular fa-folder"></i>
                    {{ $selectedService->category }}
                </span>
            </div>
        </div>
    @endif

    {{-- ======================================= --}}
    {{-- FORMULARIO DE AGENDAMIENTO              --}}
    {{-- ======================================= --}}
    <form action="{{ route('client.appointments.store') }}" method="POST" id="appointmentForm"
          class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        @csrf

        {{-- PASO 1: Servicio --}}
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 text-sm font-bold">1</span>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Elige un servicio</h3>
            </div>

            {{-- Si viene preseleccionado desde el frontend, bloquear el select --}}
            @if($selectedService)
                {{-- Hidden input para que el valor SÍ se envíe al servidor (disabled no envía) --}}
                <input type="hidden" name="service_id" value="{{ $selectedService->id }}">

                <select id="service_id" disabled
                        class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white cursor-not-allowed opacity-75">
                    <option value="{{ $selectedService->id }}" selected
                            data-duration="{{ $selectedService->duration }}"
                            data-price="{{ $selectedService->price_formatted }}"
                            data-duration-formatted="{{ $selectedService->duration_formatted }}">
                        {{ $selectedService->name }} — {{ $selectedService->price_formatted }} ({{ $selectedService->duration_formatted }})
                    </option>
                </select>
                <p class="mt-2 text-xs text-indigo-500 dark:text-indigo-400">
                    <i class="fa-solid fa-lock text-[10px] mr-1"></i>
                    Servicio seleccionado desde el catálogo.
                    <a href="{{ route('sitio.servicios') }}" class="underline hover:text-indigo-700 dark:hover:text-indigo-300">Cambiar servicio</a>
                </p>
            @else
                {{-- Si NO viene preseleccionado, el select funciona normal --}}
                <select name="service_id" id="service_id" required
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <option value="">— Selecciona un servicio —</option>
                    @php $currentCategory = ''; @endphp
                    @foreach($services as $service)
                        @if($service->category !== $currentCategory)
                            @if($currentCategory !== '') </optgroup> @endif
                            <optgroup label="{{ $service->category }}">
                            @php $currentCategory = $service->category; @endphp
                        @endif
                        <option value="{{ $service->id }}"
                                data-duration="{{ $service->duration }}"
                                data-price="{{ $service->price_formatted }}"
                                data-duration-formatted="{{ $service->duration_formatted }}">
                            {{ $service->name }} — {{ $service->price_formatted }} ({{ $service->duration_formatted }})
                        </option>
                    @endforeach
                    @if($currentCategory !== '') </optgroup> @endif
                </select>
            @endif
        </div>

        {{-- PASO 2: Estilista (se carga vía AJAX) --}}
        <div class="p-6 border-b border-gray-100 dark:border-gray-700" id="step-stylist">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-sm font-bold transition-colors" id="step2-badge">2</span>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Estilista asignado</h3>
            </div>

            {{-- Placeholder cuando no hay servicio seleccionado --}}
            <div id="stylist-placeholder" class="text-center py-6 text-gray-400 dark:text-gray-500">
                <i class="fa-solid fa-user-scissors text-2xl mb-2"></i>
                <p class="text-sm">Primero selecciona un servicio</p>
            </div>

            {{-- Loading --}}
            <div id="stylist-loading" class="hidden text-center py-6">
                <div class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-sm">Buscando estilistas...</span>
                </div>
            </div>

            {{-- Lista de estilistas (cards seleccionables) --}}
            <div id="stylist-list" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- Se rellena dinámicamente con JS --}}
            </div>

            {{-- Sin estilistas --}}
            <div id="stylist-empty" class="hidden text-center py-6 text-amber-600 dark:text-amber-400">
                <i class="fa-solid fa-triangle-exclamation text-2xl mb-2"></i>
                <p class="text-sm">No hay estilistas disponibles para este servicio.</p>
            </div>

            <input type="hidden" name="stylist_id" id="stylist_id" value="{{ old('stylist_id') }}">
        </div>

        {{-- PASO 3: Fecha --}}
        <div class="p-6 border-b border-gray-100 dark:border-gray-700" id="step-date">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-sm font-bold transition-colors" id="step3-badge">3</span>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Elige la fecha</h3>
            </div>

            <input type="date" name="date" id="date" required
                   min="{{ now()->addDay()->format('Y-m-d') }}"
                   value="{{ old('date') }}"
                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                <i class="fa-regular fa-calendar mr-1"></i>
                Lunes a Sábado. No se puede agendar para hoy ni domingo.
            </p>
        </div>

        {{-- PASO 4: Horario (se carga vía AJAX) --}}
        <div class="p-6 border-b border-gray-100 dark:border-gray-700" id="step-time">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-sm font-bold transition-colors" id="step4-badge">4</span>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Elige el horario</h3>
            </div>

            {{-- Placeholder --}}
            <div id="slots-placeholder" class="text-center py-6 text-gray-400 dark:text-gray-500">
                <i class="fa-regular fa-clock text-2xl mb-2"></i>
                <p class="text-sm">Selecciona estilista y fecha para ver horarios disponibles</p>
            </div>

            {{-- Loading --}}
            <div id="slots-loading" class="hidden text-center py-6">
                <div class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400">
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="text-sm">Verificando disponibilidad...</span>
                </div>
            </div>

            {{-- Grid de horarios --}}
            <div id="slots-grid" class="hidden grid grid-cols-3 sm:grid-cols-4 gap-2">
                {{-- Se rellena dinámicamente con JS --}}
            </div>

            {{-- Domingo o sin horarios --}}
            <div id="slots-empty" class="hidden text-center py-6 text-amber-600 dark:text-amber-400">
                <i class="fa-solid fa-calendar-xmark text-2xl mb-2"></i>
                <p class="text-sm" id="slots-empty-message">No hay horarios disponibles para esta fecha.</p>
            </div>

            <input type="hidden" name="start_time" id="start_time" value="{{ old('start_time') }}">
        </div>

        {{-- PASO 5: Notas opcionales --}}
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-4">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-sm font-bold">5</span>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Notas adicionales <span class="text-xs font-normal text-gray-400">(opcional)</span></h3>
            </div>

            <textarea name="notes" id="notes" rows="3" maxlength="500"
                      class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                      placeholder="¿Algo que debamos saber? Ej: Tengo el cabello largo, quiero un tono específico...">{{ old('notes') }}</textarea>
        </div>

        {{-- Resumen y botón --}}
        <div class="p-6 bg-gray-50 dark:bg-gray-700/30">
            {{-- Resumen dinámico --}}
            <div id="summary" class="hidden mb-5 p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3">Resumen de tu cita</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Servicio:</span>
                        <span class="font-medium text-gray-800 dark:text-white" id="summary-service">—</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Estilista:</span>
                        <span class="font-medium text-gray-800 dark:text-white" id="summary-stylist">—</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Fecha:</span>
                        <span class="font-medium text-gray-800 dark:text-white" id="summary-date">—</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Horario:</span>
                        <span class="font-medium text-gray-800 dark:text-white" id="summary-time">—</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">Precio:</span>
                        <span class="font-bold text-indigo-600 dark:text-indigo-400" id="summary-price">—</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('client.appointments.index') }}"
                   class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Mis citas
                </a>
                <button type="submit" id="submitBtn" disabled
                        class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 dark:disabled:bg-gray-600 disabled:cursor-not-allowed rounded-lg transition-colors shadow-sm">
                    <i class="fa-solid fa-calendar-check mr-1.5"></i>
                    Confirmar Cita
                </button>
            </div>
        </div>

    </form>
</div>

{{-- ======================================= --}}
{{-- JAVASCRIPT — Lógica dinámica AJAX       --}}
{{-- ======================================= --}}
@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Referencias a elementos del DOM
    const serviceSelect  = document.getElementById('service_id');
    const stylistInput   = document.getElementById('stylist_id');
    const dateInput      = document.getElementById('date');
    const startTimeInput = document.getElementById('start_time');
    const submitBtn      = document.getElementById('submitBtn');

    // Estado actual de la selección
    let selectedStylistName = '';
    let selectedSlotLabel   = '';

    // =====================================================
    // PASO 1 → Cuando cambia el servicio
    // =====================================================
    serviceSelect.addEventListener('change', function() {
        // Resetear pasos siguientes
        resetStylists();
        resetSlots();
        stylistInput.value = '';
        startTimeInput.value = '';
        updateSummary();

        const serviceId = this.value;
        if (!serviceId) return;

        // Activar badge paso 2
        activateBadge('step2-badge');

        // Mostrar loading
        showElement('stylist-loading');
        hideElement('stylist-placeholder');
        hideElement('stylist-list');
        hideElement('stylist-empty');

        // Fetch estilistas para este servicio
        fetch(`/client/appointments/stylists-by-service/${serviceId}`)
            .then(r => r.json())
            .then(data => {
                hideElement('stylist-loading');

                if (data.stylists.length === 0) {
                    showElement('stylist-empty');
                    return;
                }

                // Renderizar cards de estilistas
                const container = document.getElementById('stylist-list');
                container.innerHTML = '';

                data.stylists.forEach(stylist => {
                    const card = document.createElement('div');
                    card.className = 'stylist-card flex items-center gap-3 p-3 rounded-lg border-2 border-gray-200 dark:border-gray-600 cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-500 transition-all';
                    card.dataset.id = stylist.id;
                    card.dataset.name = stylist.name;

                    const avatar = stylist.photo
                        ? `<img src="${stylist.photo}" alt="${stylist.name}" class="w-10 h-10 rounded-full object-cover">`
                        : `<div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                             <span class="text-indigo-600 dark:text-indigo-400 font-bold text-sm">${stylist.name.charAt(0)}</span>
                           </div>`;

                    card.innerHTML = `
                        ${avatar}
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">${stylist.name}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${stylist.specialties || ''}</p>
                        </div>
                        <div class="ml-auto hidden check-icon">
                            <i class="fa-solid fa-circle-check text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                    `;

                    card.addEventListener('click', () => selectStylist(card));
                    container.appendChild(card);
                });

                showElement('stylist-list');
            })
            .catch(() => {
                hideElement('stylist-loading');
                showElement('stylist-empty');
            });
    });

    // =====================================================
    // Seleccionar estilista
    // =====================================================
    function selectStylist(card) {
        // Deseleccionar todos
        document.querySelectorAll('.stylist-card').forEach(c => {
            c.classList.remove('border-indigo-500', 'dark:border-indigo-400', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            c.classList.add('border-gray-200', 'dark:border-gray-600');
            c.querySelector('.check-icon')?.classList.add('hidden');
        });

        // Seleccionar este
        card.classList.remove('border-gray-200', 'dark:border-gray-600');
        card.classList.add('border-indigo-500', 'dark:border-indigo-400', 'bg-indigo-50', 'dark:bg-indigo-900/20');
        card.querySelector('.check-icon')?.classList.remove('hidden');

        stylistInput.value = card.dataset.id;
        selectedStylistName = card.dataset.name;

        // Activar badge paso 3
        activateBadge('step3-badge');

        // Resetear slots y cargar si ya hay fecha
        resetSlots();
        startTimeInput.value = '';
        if (dateInput.value) {
            loadAvailableSlots();
        }

        updateSummary();
    }

    // =====================================================
    // PASO 3 → Cuando cambia la fecha
    // =====================================================
    dateInput.addEventListener('change', function() {
        resetSlots();
        startTimeInput.value = '';

        const dateVal = this.value;
        if (!dateVal) return;

        // Validar que no sea domingo (JS: 0 = Domingo)
        const dayOfWeek = new Date(dateVal + 'T12:00:00').getDay();
        if (dayOfWeek === 0) {
            showElement('slots-empty');
            document.getElementById('slots-empty-message').textContent = 'Los domingos no hay servicio. Selecciona de lunes a sábado.';
            return;
        }

        // Si ya hay estilista, cargar slots
        if (stylistInput.value) {
            activateBadge('step4-badge');
            loadAvailableSlots();
        }

        updateSummary();
    });

    // =====================================================
    // Cargar horarios disponibles vía AJAX
    // =====================================================
    function loadAvailableSlots() {
        const serviceId = serviceSelect.value;
        const stylistId = stylistInput.value;
        const date = dateInput.value;

        if (!serviceId || !stylistId || !date) return;

        // Mostrar loading
        hideElement('slots-placeholder');
        hideElement('slots-grid');
        hideElement('slots-empty');
        showElement('slots-loading');

        const params = new URLSearchParams({ service_id: serviceId, stylist_id: stylistId, date: date });

        fetch(`/client/appointments/available-slots?${params}`)
            .then(r => r.json())
            .then(data => {
                hideElement('slots-loading');

                if (data.message) {
                    document.getElementById('slots-empty-message').textContent = data.message;
                    showElement('slots-empty');
                    return;
                }

                const availableSlots = data.slots.filter(s => s.available);
                if (availableSlots.length === 0) {
                    document.getElementById('slots-empty-message').textContent = 'No hay horarios disponibles para esta fecha. Prueba con otra fecha.';
                    showElement('slots-empty');
                    return;
                }

                // Renderizar grid de horarios
                const grid = document.getElementById('slots-grid');
                grid.innerHTML = '';

                data.slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.dataset.time = slot.time;
                    btn.dataset.label = slot.label;

                    if (slot.available) {
                        btn.className = 'slot-btn px-3 py-2.5 text-xs font-medium rounded-lg border-2 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-indigo-400 dark:hover:border-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all';
                        btn.textContent = slot.label.split(' - ')[0]; // Solo mostrar hora inicio
                        btn.addEventListener('click', () => selectSlot(btn));
                    } else {
                        btn.className = 'px-3 py-2.5 text-xs font-medium rounded-lg border-2 border-gray-100 dark:border-gray-700 text-gray-300 dark:text-gray-600 cursor-not-allowed line-through';
                        btn.textContent = slot.label.split(' - ')[0];
                        btn.disabled = true;
                    }

                    grid.appendChild(btn);
                });

                showElement('slots-grid');
            })
            .catch(() => {
                hideElement('slots-loading');
                document.getElementById('slots-empty-message').textContent = 'Error al cargar horarios. Intenta de nuevo.';
                showElement('slots-empty');
            });
    }

    // =====================================================
    // Seleccionar horario
    // =====================================================
    function selectSlot(btn) {
        // Deseleccionar todos
        document.querySelectorAll('.slot-btn').forEach(b => {
            b.classList.remove('border-indigo-500', 'dark:border-indigo-400', 'bg-indigo-50', 'dark:bg-indigo-900/20', 'text-indigo-700', 'dark:text-indigo-300');
            b.classList.add('border-gray-200', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
        });

        // Seleccionar este
        btn.classList.remove('border-gray-200', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
        btn.classList.add('border-indigo-500', 'dark:border-indigo-400', 'bg-indigo-50', 'dark:bg-indigo-900/20', 'text-indigo-700', 'dark:text-indigo-300');

        startTimeInput.value = btn.dataset.time;
        selectedSlotLabel = btn.dataset.label;

        updateSummary();
    }

    // =====================================================
    // Actualizar resumen y habilitar/deshabilitar botón
    // =====================================================
    function updateSummary() {
        const hasService = serviceSelect.value !== '';
        const hasStylist = stylistInput.value !== '';
        const hasDate    = dateInput.value !== '';
        const hasTime    = startTimeInput.value !== '';

        const allReady = hasService && hasStylist && hasDate && hasTime;
        submitBtn.disabled = !allReady;

        const summary = document.getElementById('summary');
        if (allReady) {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            document.getElementById('summary-service').textContent = selectedOption.text.split(' — ')[0];
            document.getElementById('summary-stylist').textContent = selectedStylistName;
            document.getElementById('summary-date').textContent = formatDateES(dateInput.value);
            document.getElementById('summary-time').textContent = selectedSlotLabel;
            document.getElementById('summary-price').textContent = selectedOption.dataset.price;
            summary.classList.remove('hidden');
        } else {
            summary.classList.add('hidden');
        }
    }

    // =====================================================
    // Helpers
    // =====================================================
    function resetStylists() {
        hideElement('stylist-list');
        hideElement('stylist-empty');
        hideElement('stylist-loading');
        showElement('stylist-placeholder');
        deactivateBadge('step2-badge');
        deactivateBadge('step3-badge');
        deactivateBadge('step4-badge');
    }

    function resetSlots() {
        hideElement('slots-grid');
        hideElement('slots-empty');
        hideElement('slots-loading');
        showElement('slots-placeholder');
        deactivateBadge('step4-badge');
    }

    function showElement(id) { document.getElementById(id)?.classList.remove('hidden'); }
    function hideElement(id) { document.getElementById(id)?.classList.add('hidden'); }

    function activateBadge(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('bg-gray-200', 'dark:bg-gray-600', 'text-gray-500', 'dark:text-gray-400');
            el.classList.add('bg-indigo-100', 'dark:bg-indigo-900/40', 'text-indigo-600', 'dark:text-indigo-400');
        }
    }

    function deactivateBadge(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('bg-indigo-100', 'dark:bg-indigo-900/40', 'text-indigo-600', 'dark:text-indigo-400');
            el.classList.add('bg-gray-200', 'dark:bg-gray-600', 'text-gray-500', 'dark:text-gray-400');
        }
    }

    function formatDateES(dateStr) {
        const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        const months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        const d = new Date(dateStr + 'T12:00:00');
        return `${days[d.getDay()]} ${d.getDate()} de ${months[d.getMonth()]}, ${d.getFullYear()}`;
    }

    // =====================================================
    // Trigger inicial si viene servicio preseleccionado
    // =====================================================
    if (serviceSelect.value) {
        serviceSelect.dispatchEvent(new Event('change'));
    }

});
</script>
@endpush

</x-admin-layout>
