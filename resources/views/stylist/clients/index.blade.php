<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Mis Clientes',
    ],
]">

{{-- PLANTILLA --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
  <!-- Header con búsqueda (solo visual) -->
  <div class="p-6 border-b border-gray-200 dark:border-gray-700">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Mis Clientes</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Clientes que has atendido</p>
      </div>

      <!-- Formulario de búsqueda (sin Blade) -->
      <form action="#" method="GET" class="flex gap-2">
        <div class="relative">
          <input
            type="text"
            name="search"
            value=""
            placeholder="Buscar cliente..."
            class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
          <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>

        <button
          type="submit"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
        >
          Buscar
        </button>

        <!-- Botón Limpiar (dejar siempre visible si solo quieres el look) -->
        <a
          href="#"
          class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition"
        >
          Limpiar
        </a>
      </form>
    </div>
  </div>

  <!-- Grid de Clientes -->
  <div class="p-6">
    <!-- Vista con clientes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <!-- Card 1 (con imagen) -->
      <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:shadow-md transition">
        <div class="flex items-start gap-4">
          <!-- Avatar -->
          <div class="flex-shrink-0">
            <img
              src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&h=200&fit=crop&crop=faces"
              alt="Ana López"
              class="w-14 h-14 rounded-full object-cover"
            />
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-gray-900 dark:text-white truncate">Ana López</h3>

            <div class="mt-1 space-y-1">
              <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                <i class="fa-solid fa-phone text-xs"></i>
                +52 271 000 0000
              </p>
              <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                <i class="fa-solid fa-envelope text-xs"></i>
                <span class="truncate">ana.lopez@mail.com</span>
              </p>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="mt-3 flex items-center gap-4 text-xs">
              <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                <i class="fa-solid fa-calendar-check"></i>
                12 citas
              </span>
              <span class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-clock"></i>
                Última: 20/01/2026
              </span>
            </div>
          </div>
        </div>

        <!-- Acciones -->
        <div
          class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center"
        >
          <a
            href="#"
            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
          >
            Ver historial
            <i class="fa-solid fa-arrow-right ml-1"></i>
          </a>

          <a
            href="#"
            class="p-2 text-green-600 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-full transition"
            title="Llamar"
          >
            <i class="fa-solid fa-phone"></i>
          </a>
        </div>
      </div>

      <!-- Card 2 (sin imagen: inicial) -->
      <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:shadow-md transition">
        <div class="flex items-start gap-4">
          <!-- Avatar -->
          <div class="flex-shrink-0">
            <div
              class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold"
            >
              K
            </div>
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-gray-900 dark:text-white truncate">Karla Méndez</h3>

            <div class="mt-1 space-y-1">
              <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                <i class="fa-solid fa-phone text-xs"></i>
                +52 271 111 1111
              </p>
              <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                <i class="fa-solid fa-envelope text-xs"></i>
                <span class="truncate">karla.mendez@mail.com</span>
              </p>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="mt-3 flex items-center gap-4 text-xs">
              <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                <i class="fa-solid fa-calendar-check"></i>
                4 citas
              </span>
              <span class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-clock"></i>
                Última: 15/01/2026
              </span>
            </div>
          </div>
        </div>

        <!-- Acciones -->
        <div
          class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center"
        >
          <a
            href="#"
            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
          >
            Ver historial
            <i class="fa-solid fa-arrow-right ml-1"></i>
          </a>

          <a
            href="#"
            class="p-2 text-green-600 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-full transition"
            title="Llamar"
          >
            <i class="fa-solid fa-phone"></i>
          </a>
        </div>
      </div>

      <!-- Card 3 (sin teléfono: solo email, sin botón de llamada) -->
      <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hover:shadow-md transition">
        <div class="flex items-start gap-4">
          <!-- Avatar -->
          <div class="flex-shrink-0">
            <div
              class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold"
            >
              M
            </div>
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-gray-900 dark:text-white truncate">María Hernández</h3>

            <div class="mt-1 space-y-1">
              <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                <i class="fa-solid fa-envelope text-xs"></i>
                <span class="truncate">maria.hdz@mail.com</span>
              </p>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="mt-3 flex items-center gap-4 text-xs">
              <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                <i class="fa-solid fa-calendar-check"></i>
                1 citas
              </span>
              <span class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                <i class="fa-solid fa-clock"></i>
                Última: 05/01/2026
              </span>
            </div>
          </div>
        </div>

        <!-- Acciones -->
        <div
          class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center"
        >
          <a
            href="#"
            class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
          >
            Ver historial
            <i class="fa-solid fa-arrow-right ml-1"></i>
          </a>

          <!-- Sin botón de llamada -->
          <span class="text-xs text-gray-400 dark:text-gray-500">Sin teléfono</span>
        </div>
      </div>
    </div>

    <!-- Paginación (solo visual) -->
    <div class="mt-6">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Mostrando <span class="font-medium text-gray-900 dark:text-white">1</span> a
          <span class="font-medium text-gray-900 dark:text-white">3</span> de
          <span class="font-medium text-gray-900 dark:text-white">12</span> clientes
        </p>

        <nav class="inline-flex -space-x-px rounded-md shadow-sm" aria-label="Paginación">
          <a
            href="#"
            class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-200 rounded-l-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700"
          >
            Anterior
          </a>

          <a
            href="#"
            class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700"
          >
            1
          </a>

          <a
            href="#"
            class="px-3 py-2 text-sm font-semibold text-white bg-blue-600 border border-blue-600 hover:bg-blue-700"
          >
            2
          </a>

          <a
            href="#"
            class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700"
          >
            3
          </a>

          <a
            href="#"
            class="px-3 py-2 text-sm text-gray-500 bg-white border border-gray-200 rounded-r-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700"
          >
            Siguiente
          </a>
        </nav>
      </div>
    </div>

    <!-- Estado vacío (si quieres mostrar "sin clientes", usa SOLO este bloque y quita grid+paginación) -->
    <!--
    <div class="text-center py-12">
      <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
        <i class="fa-solid fa-users text-3xl text-gray-400"></i>
      </div>

      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
        Aún no tienes clientes
      </h3>

      <p class="text-gray-500 dark:text-gray-400">
        Cuando atiendas tu primera cita, tus clientes aparecerán aquí.
      </p>
    </div>
    -->
  </div>
</div>

{{-- PLANTILLA --}}

</x-admin-layout>
