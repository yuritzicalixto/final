<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Mi Carrito',
    ],
]">

{{-- PLANTILLA --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <!-- Lista de Productos -->
  <div class="lg:col-span-2">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
          <i class="fa-solid fa-cart-shopping text-yellow-500 mr-3"></i>
          Mi Carrito
          <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">(2/5 productos)</span>
        </h2>
      </div>

      <!-- ======= ESTADO: CON PRODUCTOS (visual) ======= -->
      <div class="divide-y divide-gray-200 dark:divide-gray-700">
        <!-- Item 1 -->
        <div class="p-6">
          <div class="flex gap-4">
            <!-- Imagen del producto -->
            <div class="w-24 h-24 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
              <img
                src="https://via.placeholder.com/200"
                alt="Shampoo Reparador"
                class="w-full h-full object-cover"
              />
              <!-- Sin imagen (alternativa) -->
              <!--
              <div class="w-full h-full flex items-center justify-center">
                <i class="fa-solid fa-box text-2xl text-gray-400"></i>
              </div>
              -->
            </div>

            <!-- Info del producto -->
            <div class="flex-1 min-w-0">
              <div class="flex justify-between gap-4">
                <div class="min-w-0">
                  <h3 class="font-semibold text-gray-900 dark:text-white truncate">Shampoo Reparador</h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Marca X • Cuidado capilar</p>
                  <p class="text-yellow-600 dark:text-yellow-400 font-semibold mt-1">$249.00 c/u</p>
                </div>

                <!-- Subtotal -->
                <div class="text-right whitespace-nowrap">
                  <p class="text-lg font-bold text-gray-900 dark:text-white">$498.00</p>
                </div>
              </div>

              <!-- Controles de cantidad -->
              <div class="mt-4 flex items-center justify-between gap-4 flex-wrap">
                <form action="#" method="POST" class="flex items-center gap-2">
                  <label class="text-sm text-gray-500 dark:text-gray-400">Cantidad:</label>
                  <select
                    name="quantity"
                    class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 dark:bg-gray-700 dark:text-white text-sm"
                  >
                    <option value="1">1</option>
                    <option value="2" selected>2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                  </select>
                  <span class="text-xs text-gray-400">(Stock: 12)</span>
                </form>

                <form action="#" method="POST">
                  <button type="button" class="text-red-500 hover:text-red-700 text-sm font-medium">
                    <i class="fa-solid fa-trash mr-1"></i>
                    Eliminar
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 2 -->
        <div class="p-6">
          <div class="flex gap-4">
            <div class="w-24 h-24 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
              <div class="w-full h-full flex items-center justify-center">
                <i class="fa-solid fa-box text-2xl text-gray-400"></i>
              </div>
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex justify-between gap-4">
                <div class="min-w-0">
                  <h3 class="font-semibold text-gray-900 dark:text-white truncate">Cera Modeladora Mate</h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Marca Y • Styling</p>
                  <p class="text-yellow-600 dark:text-yellow-400 font-semibold mt-1">$180.00 c/u</p>
                </div>

                <div class="text-right whitespace-nowrap">
                  <p class="text-lg font-bold text-gray-900 dark:text-white">$180.00</p>
                </div>
              </div>

              <div class="mt-4 flex items-center justify-between gap-4 flex-wrap">
                <form action="#" method="POST" class="flex items-center gap-2">
                  <label class="text-sm text-gray-500 dark:text-gray-400">Cantidad:</label>
                  <select
                    name="quantity"
                    class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 dark:bg-gray-700 dark:text-white text-sm"
                  >
                    <option value="1" selected>1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                  </select>
                  <span class="text-xs text-gray-400">(Stock: 4)</span>
                </form>

                <form action="#" method="POST">
                  <button type="button" class="text-red-500 hover:text-red-700 text-sm font-medium">
                    <i class="fa-solid fa-trash mr-1"></i>
                    Eliminar
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Vaciar carrito -->
      <div class="p-6 border-t border-gray-200 dark:border-gray-700">
        <form action="#" method="POST">
          <button
            type="button"
            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm"
          >
            <i class="fa-solid fa-trash mr-1"></i>
            Vaciar carrito
          </button>
        </form>
      </div>

      <!-- ======= ESTADO: CARRITO VACÍO (visual) ======= -->
      <!--
      <div class="p-12 text-center">
        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
          <i class="fa-solid fa-cart-shopping text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tu carrito está vacío</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-4">Explora nuestros productos y agrega los que te gusten</p>
        <a
          href="#"
          class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-medium rounded-lg transition"
        >
          <i class="fa-solid fa-box mr-2"></i>
          Ver Productos
        </a>
      </div>
      -->
    </div>
  </div>

  <!-- Resumen del Pedido -->
  <div class="lg:col-span-1">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow sticky top-4">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Resumen del Apartado</h3>
      </div>

      <div class="p-6 space-y-4">
        <div class="flex justify-between text-gray-600 dark:text-gray-400">
          <span>Productos (3)</span>
          <span>$678.00</span>
        </div>

        <hr class="border-gray-200 dark:border-gray-700" />

        <div class="flex justify-between text-lg font-bold">
          <span class="text-gray-900 dark:text-white">Total</span>
          <span class="text-yellow-600 dark:text-yellow-400">$678.00</span>
        </div>

        <!-- Botón confirmar (solo visual) -->
        <form action="#" method="POST">
          <button
            type="button"
            class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition"
          >
            <i class="fa-solid fa-check mr-2"></i>
            Confirmar Apartado
          </button>
        </form>

        <a
          href="#"
          class="block w-full py-3 text-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition"
        >
          <i class="fa-solid fa-arrow-left mr-2"></i>
          Seguir comprando
        </a>
      </div>

      <!-- Info del apartado -->
      <div class="p-6 bg-blue-50 dark:bg-blue-900/20 border-t border-blue-100 dark:border-blue-800">
        <div class="flex items-start gap-3 text-sm text-blue-700 dark:text-blue-300">
          <i class="fa-solid fa-info-circle mt-0.5"></i>
          <div>
            <p class="font-medium">¿Cómo funciona el apartado?</p>
            <ul class="list-disc list-inside mt-2 space-y-1 text-blue-600 dark:text-blue-400">
              <li>Tienes 7 días para recoger tus productos</li>
              <li>El pago se realiza al recoger</li>
              <li>Máximo 5 productos diferentes</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- PLANTILLA --}}

</x-admin-layout>
