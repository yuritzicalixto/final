<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Apartados',
        'route' => route('client.reservations.index'),
    ],
    [
        'name'=> 'Confirmar Apartado',
    ],
]">

<div class="max-w-3xl mx-auto py-8 px-4">

    <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Confirmar Apartado</h1>

    {{-- Contenedor donde JS renderiza los productos del carrito --}}
    <div id="reservationItems" class="space-y-4 mb-6"></div>

    {{-- Mensaje si el carrito está vacío --}}
    <div id="emptyCartMessage" style="display: none;" class="text-center py-12 text-gray-400">
        <i class="fa-solid fa-box-open text-5xl mb-4"></i>
        <p class="text-lg">Tu carrito está vacío.</p>
        <a href="{{ route('sitio.productos') }}" class="text-indigo-600 underline mt-2 inline-block">
            Ir a productos
        </a>
    </div>

    {{-- Total --}}
    <div id="reservationTotal" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6 flex justify-between items-center" style="display: none;">
        <span class="text-lg font-medium text-gray-700 dark:text-gray-200">Total del apartado:</span>
        <span id="totalAmount" class="text-2xl font-bold text-gray-900 dark:text-white">$0.00</span>
    </div>

    {{-- ═══════════════════════════════════════════════
         SELECTOR DE FECHA DE RECOLECCIÓN
         ═══════════════════════════════════════════════ --}}
    <div id="pickupSection" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-6" style="display: none;">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
            <i class="fa-solid fa-calendar-days text-indigo-500 mr-2"></i>
            ¿Cuándo pasarás a recoger? (opcional)
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Nuestro horario: <strong>Lunes a Sábado, 10:00 AM - 5:00 PM</strong>. Domingos cerrado.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="pickup_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Día de recolección
                </label>
                <select id="pickup_date" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">— Sin preferencia —</option>
                    {{-- Las opciones se llenan por JS con los días hábiles --}}
                </select>
            </div>
            <div>
                <label for="pickup_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Hora aproximada
                </label>
                <select id="pickup_time" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">— Sin preferencia —</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="13:00">1:00 PM</option>
                    <option value="14:00">2:00 PM</option>
                    <option value="15:00">3:00 PM</option>
                    <option value="16:00">4:00 PM</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Información del apartado --}}
    <div id="reservationInfo" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6" style="display: none;">
        <p class="text-sm text-blue-800 dark:text-blue-300">
            <i class="fa-solid fa-circle-info mr-1"></i>
            <strong>Importante:</strong> Al confirmar, los productos se reservarán por
            <strong>7 días</strong>. Deberás pasar al salón a recogerlos y pagarlos antes de la fecha de vencimiento.
            Si no los recoges, el apartado se cancela automáticamente y los productos regresan al inventario.
        </p>
    </div>

    {{-- Formulario oculto que JS llena con los datos del carrito --}}
    <form id="reservationForm" action="{{ route('client.reservations.store') }}" method="POST">
        @csrf
        <div id="formInputs"></div>
        {{-- Input oculto para la fecha de recolección preferida --}}
        <input type="hidden" name="preferred_pickup_date" id="preferred_pickup_input" value="">

        <div id="confirmBtnContainer" class="flex gap-3 justify-end" style="display: none;">
            <a href="{{ route('sitio.productos') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                Seguir comprando
            </a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition font-medium">
                <i class="fa-solid fa-check mr-1"></i> Confirmar Apartado
            </button>
        </div>
    </form>

    {{-- Errores de validación --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

{{-- ============================================================ --}}
{{-- JavaScript: Lee localStorage y renderiza los productos       --}}
{{-- ============================================================ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const savedCart = localStorage.getItem('salonCart');
    const cart = savedCart ? JSON.parse(savedCart) : [];

    const itemsContainer    = document.getElementById('reservationItems');
    const emptyMessage      = document.getElementById('emptyCartMessage');
    const totalContainer    = document.getElementById('reservationTotal');
    const totalAmount       = document.getElementById('totalAmount');
    const infoBox           = document.getElementById('reservationInfo');
    const formInputs        = document.getElementById('formInputs');
    const confirmBtn        = document.getElementById('confirmBtnContainer');
    const pickupSection     = document.getElementById('pickupSection');
    const pickupDateSelect  = document.getElementById('pickup_date');
    const pickupTimeSelect  = document.getElementById('pickup_time');
    const pickupInput       = document.getElementById('preferred_pickup_input');

    if (cart.length === 0) {
        emptyMessage.style.display = 'block';
        return;
    }

    // Mostrar elementos
    totalContainer.style.display = 'flex';
    infoBox.style.display        = 'block';
    confirmBtn.style.display     = 'flex';
    pickupSection.style.display  = 'block';

    // ─── Generar opciones de días hábiles (L-S, próximos 7 días) ───
    const dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const today = new Date();

    for (let i = 1; i <= 7; i++) {
        const d = new Date(today);
        d.setDate(today.getDate() + i);

        // Excluir domingos (0 = domingo)
        if (d.getDay() === 0) continue;

        const option = document.createElement('option');
        // Formato YYYY-MM-DD para enviar al servidor
        const yyyy = d.getFullYear();
        const mm   = String(d.getMonth() + 1).padStart(2, '0');
        const dd   = String(d.getDate()).padStart(2, '0');
        option.value = `${yyyy}-${mm}-${dd}`;
        option.textContent = `${dayNames[d.getDay()]} ${dd}/${mm}/${yyyy}`;
        pickupDateSelect.appendChild(option);
    }

    // ─── Actualizar el input hidden cuando cambie fecha u hora ───
    function updatePickupInput() {
        const date = pickupDateSelect.value;
        const time = pickupTimeSelect.value;
        if (date && time) {
            pickupInput.value = `${date} ${time}:00`;
        } else if (date) {
            pickupInput.value = `${date} 10:00:00`; // Default: hora de apertura
        } else {
            pickupInput.value = '';
        }
    }
    pickupDateSelect.addEventListener('change', updatePickupInput);
    pickupTimeSelect.addEventListener('change', updatePickupInput);

    // ─── Renderizar productos ───
    let total = 0;

    cart.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        const itemEl = document.createElement('div');
        itemEl.className = 'bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex items-center gap-4';
        itemEl.innerHTML = `
            <img src="${item.image}" alt="${item.name}"
                 class="w-16 h-16 object-cover rounded-lg"
                 onerror="this.src='/images/default-product.jpg'">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-800 dark:text-white">${item.name}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Cantidad: ${item.quantity}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Precio unitario: $${item.price.toLocaleString('es-MX', {minimumFractionDigits: 2})}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-800 dark:text-white">$${subtotal.toLocaleString('es-MX', {minimumFractionDigits: 2})}</p>
            </div>
        `;
        itemsContainer.appendChild(itemEl);

        // Inputs hidden para el form
        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = `items[${index}][product_id]`;
        inputId.value = item.id;
        formInputs.appendChild(inputId);

        const inputQty = document.createElement('input');
        inputQty.type = 'hidden';
        inputQty.name = `items[${index}][quantity]`;
        inputQty.value = item.quantity;
        formInputs.appendChild(inputQty);
    });

    totalAmount.textContent = `$${total.toLocaleString('es-MX', {minimumFractionDigits: 2})}`;

    // Al enviar, limpiar carrito
    document.getElementById('reservationForm').addEventListener('submit', function () {
        localStorage.removeItem('salonCart');
    });
});
</script>

</x-admin-layout>
