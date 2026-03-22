<x-admin-layout :breadcrumbs="[
    ['name'=> 'Dashboard'],
]">

{{-- Saludo + Tabs --}}
<div class="mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 mb-4 flex items-center justify-between">
        <div class="flex items-center">
            <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
            <div class="ml-3">
                <h2 class="text-lg font-semibold dark:text-white">Hola, {{ auth()->user()->name }}</h2>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="text-sm text-gray-500 hover:text-blue-500">Cerrar sesión</button>
                </form>
            </div>
        </div>
        <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200"></h2>
    </div>
</div>

@role('admin')
{{-- Dashboard con Tabs --}}
<div x-data="{ tab: 'servicios' }">

    {{-- Tab Navigation --}}
    <div class="border-b border-gray-200 dark:border-gray-700 mb-6 overflow-x-auto">
        <nav class="flex space-x-1 min-w-max" aria-label="Tabs">
            <button @click="tab = 'servicios'" :class="tab === 'servicios' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                <i class="fa-solid fa-scissors mr-1"></i> Servicios
            </button>
            <button @click="tab = 'estilistas'" :class="tab === 'estilistas' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                <i class="fa-solid fa-users-gear mr-1"></i> Estilistas
            </button>
            <button @click="tab = 'clientes'" :class="tab === 'clientes' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                <i class="fa-solid fa-users mr-1"></i> Clientes
            </button>
            <button @click="tab = 'productos'" :class="tab === 'productos' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                <i class="fa-solid fa-cubes-stacked mr-1"></i> Productos
            </button>
            <button @click="tab = 'kpis'" :class="tab === 'kpis' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">
                <i class="fa-solid fa-gauge-high mr-1"></i> KPIs
            </button>
        </nav>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TAB 1: ANÁLISIS DE SERVICIOS              --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-show="tab === 'servicios'" x-cloak>
        {{-- KPIs --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total de Citas</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($data['servicios']['totalCitas']) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Ingresos por Servicios</p>
                <p class="text-2xl font-bold text-green-600 mt-1">${{ number_format($data['servicios']['ingresos'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Tasa de Completación</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $data['servicios']['tasaCompletacion'] }}%</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Ticket Promedio</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">${{ number_format($data['servicios']['ticketPromedio'], 2) }}</p>
            </div>
        </div>

        {{-- Gráficas --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Servicios Más Demandados</h3>
                <div id="chart-servicios-demandados"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Ingresos por Servicio</h3>
                <div id="chart-ingresos-servicio"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Tendencia Mensual de Ingresos</h3>
            <div id="chart-tendencia-ingresos"></div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TAB 2: PRODUCTIVIDAD DE ESTILISTAS        --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-show="tab === 'estilistas'" x-cloak>
        {{-- KPIs --}}
        @php
            $totalEstCitas = $data['estilistas']['porEstilista']->sum('total_citas');
            $totalEstCompletadas = $data['estilistas']['porEstilista']->sum('completadas');
            $totalEstIngresos = $data['estilistas']['porEstilista']->sum('ingresos');
            $tasaEstComp = $totalEstCitas > 0 ? round(($totalEstCompletadas / $totalEstCitas) * 100, 1) : 0;
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total de Citas</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($totalEstCitas) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Citas Completadas</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($totalEstCompletadas) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Ingresos Generados</p>
                <p class="text-2xl font-bold text-green-600 mt-1">${{ number_format($totalEstIngresos, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Tasa de Completación</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $tasaEstComp }}%</p>
            </div>
        </div>

        {{-- Gráficas --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Citas Atendidas por Estilista</h3>
                <div id="chart-estilistas-citas"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Ingresos por Estilista</h3>
                <div id="chart-estilistas-ingresos"></div>
            </div>
        </div>

        {{-- Tabla Ranking --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Ranking de Desempeño</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Estilista</th>
                            <th class="px-4 py-3 text-center">Completadas</th>
                            <th class="px-4 py-3 text-right">Ingresos</th>
                            <th class="px-4 py-3 text-center">Tasa Compl.</th>
                            <th class="px-4 py-3 text-center">No Shows</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['estilistas']['porEstilista'] as $est)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 font-medium dark:text-white">{{ $est->estilista }}</td>
                            <td class="px-4 py-3 text-center dark:text-gray-300">{{ $est->completadas }}</td>
                            <td class="px-4 py-3 text-right text-green-600">${{ number_format($est->ingresos, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $est->tasa_completacion >= 70 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $est->tasa_completacion }}%
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $est->tasa_no_show <= 5 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $est->tasa_no_show }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Evolución Mensual por Estilista</h3>
            <div id="chart-evolucion-estilistas"></div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TAB 3: ANÁLISIS DE CLIENTES (RFM)         --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-show="tab === 'clientes'" x-cloak>
        {{-- KPIs --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Clientes Atendidos</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $data['clientes']['totalClientes'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Clientes VIP</p>
                <p class="text-2xl font-bold text-yellow-500 mt-1">{{ $data['clientes']['clientesVIP'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Clientes en Riesgo</p>
                <p class="text-2xl font-bold text-red-500 mt-1">{{ $data['clientes']['clientesRiesgo'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Promedio Visitas/Cliente</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $data['clientes']['promedioVisitas'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Clientes por Segmento RFM</h3>
                <div id="chart-segmentos-rfm"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Ingresos por Segmento</h3>
                <div id="chart-ingresos-segmento"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Top 15 Clientes por Frecuencia</h3>
                <div id="chart-top-clientes"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Servicio Preferido</h3>
                <div id="chart-servicio-preferido"></div>
            </div>
        </div>

        {{-- Tabla RFM --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Detalle RFM por Cliente</h3>
            <div class="overflow-x-auto max-h-96 overflow-y-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 sticky top-0">
                        <tr>
                            <th class="px-3 py-2">Cliente</th>
                            <th class="px-3 py-2 text-center">Segmento</th>
                            <th class="px-3 py-2 text-center">Visitas</th>
                            <th class="px-3 py-2 text-right">Gastado</th>
                            <th class="px-3 py-2 text-center">Días sin venir</th>
                            <th class="px-3 py-2 text-center">R-F-M</th>
                            <th class="px-3 py-2 text-center">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['clientes']['clientes'] as $c)
                        @php
                            $segColor = match($c->segmento) {
                                'VIP' => 'bg-yellow-100 text-yellow-800',
                                'Leal' => 'bg-blue-100 text-blue-800',
                                'Regular' => 'bg-gray-100 text-gray-800',
                                'En Riesgo' => 'bg-orange-100 text-orange-800',
                                'Perdido' => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-3 py-2 font-medium dark:text-white">{{ $c->name }}</td>
                            <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-xs {{ $segColor }}">{{ $c->segmento }}</span></td>
                            <td class="px-3 py-2 text-center dark:text-gray-300">{{ $c->total_visitas }}</td>
                            <td class="px-3 py-2 text-right text-green-600">${{ number_format($c->total_gastado, 2) }}</td>
                            <td class="px-3 py-2 text-center dark:text-gray-300">{{ $c->dias_sin_visitar }}d</td>
                            <td class="px-3 py-2 text-center dark:text-gray-300">{{ $c->r }}-{{ $c->f }}-{{ $c->m }}</td>
                            <td class="px-3 py-2 text-center font-bold dark:text-white">{{ $c->rfm_score }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TAB 4: VENTA DE PRODUCTOS                 --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-show="tab === 'productos'" x-cloak>
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total Apartados</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $data['productos']['totalApartados'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Ingresos Apartados</p>
                <p class="text-2xl font-bold text-green-600 mt-1">${{ number_format($data['productos']['ingresosApartados'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Tasa de Conversión</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $data['productos']['tasaConversion'] }}%</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Stock Bajo (&lt;5)</p>
                <p class="text-2xl font-bold text-orange-500 mt-1">{{ $data['productos']['stockBajo'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Valor Inventario</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">${{ number_format($data['productos']['valorInventario'], 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Top 10 Productos Más Apartados</h3>
                <div id="chart-top-productos"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Ingresos por Categoría</h3>
                <div id="chart-ingresos-categoria"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            {{-- Tabla Inventario Crítico --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">
                    <i class="fa-solid fa-triangle-exclamation text-orange-500 mr-1"></i> Inventario Crítico
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-3 py-2">Producto</th>
                                <th class="px-3 py-2">Marca</th>
                                <th class="px-3 py-2 text-center">Stock</th>
                                <th class="px-3 py-2 text-right">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['productos']['inventarioCritico'] as $prod)
                            @php
                                $stockColor = $prod->stock <= 0 ? 'bg-red-100 text-red-700' : ($prod->stock < 5 ? 'bg-orange-100 text-orange-700' : 'bg-yellow-100 text-yellow-700');
                            @endphp
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-3 py-2 font-medium dark:text-white">{{ $prod->name }}</td>
                                <td class="px-3 py-2 text-gray-500">{{ $prod->brand }}</td>
                                <td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $stockColor }}">{{ $prod->stock }}</span></td>
                                <td class="px-3 py-2 text-right dark:text-gray-300">${{ number_format($prod->price, 2) }}</td>
                            </tr>
                            @endforeach
                            @if(count($data['productos']['inventarioCritico']) === 0)
                            <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400">Sin productos en nivel crítico</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Apartados por Estado</h3>
                <div id="chart-estado-apartados"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Tendencia Mensual de Apartados</h3>
            <div id="chart-tendencia-apartados"></div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TAB 5: KPIs PRINCIPALES                   --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-show="tab === 'kpis'" x-cloak>
        {{-- Sección Financiera --}}
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-2 font-semibold">Finanzas</p>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-5 text-white">
                <p class="text-xs uppercase opacity-80">Ingreso Total del Negocio</p>
                <p class="text-3xl font-bold mt-1">${{ number_format($data['kpis']['ingresoTotal'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                <p class="text-xs text-gray-500 uppercase">Servicios ({{ $data['kpis']['pctServicios'] }}%)</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">${{ number_format($data['kpis']['ingresosServicios'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
                <p class="text-xs text-gray-500 uppercase">Productos ({{ $data['kpis']['pctProductos'] }}%)</p>
                <p class="text-2xl font-bold text-purple-600 mt-1">${{ number_format($data['kpis']['ingresosProductos'], 2) }}</p>
            </div>
        </div>

        {{-- Sección Operación --}}
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-2 font-semibold">Operación</p>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase">Total de Citas</p>
                <p class="text-2xl font-bold dark:text-white mt-1">{{ number_format($data['kpis']['totalCitas']) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase">Tasa de Completación</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $data['kpis']['tasaCompletacion'] }}%</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase">Promedio Citas/Día</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $data['kpis']['citasPorDia'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase">Ingreso Promedio/Día</p>
                <p class="text-2xl font-bold text-green-600 mt-1">${{ number_format($data['kpis']['ingresoPorDia'], 2) }}</p>
            </div>
        </div>

        {{-- Sección Clientes --}}
        <p class="text-xs text-gray-400 uppercase tracking-widest mb-2 font-semibold">Clientes</p>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase">Clientes Activos</p>
                <p class="text-2xl font-bold dark:text-white mt-1">{{ $data['kpis']['clientesActivos'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase">Ticket Promedio</p>
                <p class="text-2xl font-bold text-green-600 mt-1">${{ number_format($data['kpis']['ticketPromedio'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase">Tasa de Inasistencia</p>
                <p class="text-2xl font-bold text-red-500 mt-1">{{ $data['kpis']['tasaNoShow'] }}%</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <p class="text-xs text-gray-500 uppercase">Productos Stock Bajo</p>
                <p class="text-2xl font-bold text-orange-500 mt-1">{{ $data['kpis']['stockBajo'] }}</p>
            </div>
        </div>

        {{-- Gráficas --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Composición de Ingresos</h3>
                <div id="chart-composicion-ingresos"></div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Citas por Estado</h3>
                <div id="chart-citas-estado"></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-3">Tendencia de Ingresos Mensuales</h3>
            <div id="chart-tendencia-kpis"></div>
        </div>
    </div>

</div>
@endrole

@role('admin')
@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Helpers ──
    const meses = @json($data['servicios']['ingresosMensuales']->pluck('mes'));
    const mesLabels = meses.map(m => {
        const [y, mo] = m.split('-');
        const nombres = ['','ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
        return nombres[parseInt(mo)] + ' ' + y;
    });

    const chartOpts = (overrides) => Object.assign({
        chart: { toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        colors: ['#3B82F6','#10B981','#8B5CF6','#F59E0B','#EF4444','#EC4899'],
        tooltip: { theme: 'light' },
    }, overrides);

    // ══════════════════════════════════════════════
    // DASHBOARD 1: SERVICIOS
    // ══════════════════════════════════════════════

    // Barras — Servicios más demandados
    new ApexCharts(document.querySelector("#chart-servicios-demandados"), chartOpts({
        chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{ name: 'Citas', data: @json($data['servicios']['serviciosDemandados']->pluck('total')) }],
        xaxis: { categories: @json($data['servicios']['serviciosDemandados']->pluck('name')) },
        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
        dataLabels: { enabled: true },
        colors: ['#3B82F6'],
    })).render();

    // Pie — Ingresos por servicio
    new ApexCharts(document.querySelector("#chart-ingresos-servicio"), chartOpts({
        chart: { type: 'pie', height: 320, fontFamily: 'Figtree, sans-serif' },
        series: @json($data['servicios']['ingresosPorServicio']->pluck('total')->map(fn($v) => round($v, 2))),
        labels: @json($data['servicios']['ingresosPorServicio']->pluck('name')),
        legend: { position: 'bottom', fontSize: '11px' },
    })).render();

    // Línea — Tendencia ingresos mensuales
    new ApexCharts(document.querySelector("#chart-tendencia-ingresos"), chartOpts({
        chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{
            name: 'Ingresos',
            data: @json($data['servicios']['ingresosMensuales']->pluck('total')->map(fn($v) => round($v, 2)))
        }],
        xaxis: { categories: mesLabels },
        yaxis: { labels: { formatter: v => '$' + Math.round(v).toLocaleString() } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        colors: ['#10B981'],
    })).render();

    // ══════════════════════════════════════════════
    // DASHBOARD 2: ESTILISTAS
    // ══════════════════════════════════════════════
    const estData = @json($data['estilistas']['porEstilista']);
    const estNombres = estData.map(e => e.estilista);

    new ApexCharts(document.querySelector("#chart-estilistas-citas"), chartOpts({
        chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{ name: 'Completadas', data: estData.map(e => e.completadas) }],
        xaxis: { categories: estNombres },
        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
        dataLabels: { enabled: true },
        colors: ['#3B82F6'],
    })).render();

    new ApexCharts(document.querySelector("#chart-estilistas-ingresos"), chartOpts({
        chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{ name: 'Ingresos', data: estData.map(e => parseFloat(e.ingresos)) }],
        xaxis: { categories: estNombres },
        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
        dataLabels: { enabled: true, formatter: v => '$' + Math.round(v).toLocaleString() },
        colors: ['#10B981'],
    })).render();

    // Evolución estilistas
    const evolEst = @json($data['estilistas']['evolucionEstilistas']);
    const evolMeses = [...new Set(evolEst.map(e => e.mes))].sort();
    const evolEstNames = [...new Set(evolEst.map(e => e.estilista))];
    const evolSeries = evolEstNames.map(name => ({
        name: name,
        data: evolMeses.map(mes => {
            const found = evolEst.find(e => e.mes === mes && e.estilista === name);
            return found ? found.total : 0;
        })
    }));

    new ApexCharts(document.querySelector("#chart-evolucion-estilistas"), chartOpts({
        chart: { type: 'line', height: 300, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: evolSeries,
        xaxis: { categories: evolMeses.map(m => { const [y,mo]=m.split('-'); const n=['','ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic']; return n[parseInt(mo)]+' '+y; }) },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
    })).render();

    // ══════════════════════════════════════════════
    // DASHBOARD 3: CLIENTES
    // ══════════════════════════════════════════════
    const segmentos = @json($data['clientes']['segmentos']);
    const segColores = { 'VIP': '#F59E0B', 'Leal': '#3B82F6', 'Regular': '#6B7280', 'En Riesgo': '#F97316', 'Perdido': '#EF4444' };
    const segOrder = ['VIP','Leal','Regular','En Riesgo','Perdido'];
    const segLabels = segOrder.filter(s => segmentos[s]);
    const segValues = segLabels.map(s => segmentos[s]);
    const segColors = segLabels.map(s => segColores[s]);

    new ApexCharts(document.querySelector("#chart-segmentos-rfm"), chartOpts({
        chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{ name: 'Clientes', data: segValues }],
        xaxis: { categories: segLabels },
        plotOptions: { bar: { borderRadius: 6, distributed: true } },
        colors: segColors,
        dataLabels: { enabled: true, style: { fontSize: '14px' } },
        legend: { show: false },
    })).render();

    const ingSegmento = @json($data['clientes']['ingresosPorSegmento']);
    const ingSegLabels = segOrder.filter(s => ingSegmento[s]);
    const ingSegValues = ingSegLabels.map(s => ingSegmento[s]);
    const ingSegColors = ingSegLabels.map(s => segColores[s]);

    new ApexCharts(document.querySelector("#chart-ingresos-segmento"), chartOpts({
        chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{ name: 'Ingresos', data: ingSegValues }],
        xaxis: { categories: ingSegLabels },
        plotOptions: { bar: { borderRadius: 6, distributed: true } },
        colors: ingSegColors,
        dataLabels: { enabled: true, formatter: v => '$' + Math.round(v).toLocaleString() },
        legend: { show: false },
    })).render();

    // Top 15 clientes
    const topClientes = @json($data['clientes']['clientes']->sortByDesc('total_visitas')->take(15)->values());
    new ApexCharts(document.querySelector("#chart-top-clientes"), chartOpts({
        chart: { type: 'bar', height: 380, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{ name: 'Visitas', data: topClientes.map(c => c.total_visitas) }],
        xaxis: { categories: topClientes.map(c => c.name) },
        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
        dataLabels: { enabled: true },
        colors: ['#8B5CF6'],
    })).render();

    // Servicio preferido
    const servPref = @json($data['clientes']['servicioPref']);
    new ApexCharts(document.querySelector("#chart-servicio-preferido"), chartOpts({
        chart: { type: 'pie', height: 380, fontFamily: 'Figtree, sans-serif' },
        series: servPref.map(s => s.total),
        labels: servPref.map(s => s.name),
        legend: { position: 'bottom', fontSize: '10px' },
    })).render();

    // ══════════════════════════════════════════════
    // DASHBOARD 4: PRODUCTOS
    // ══════════════════════════════════════════════
    const topProd = @json($data['productos']['topProductos']);
    new ApexCharts(document.querySelector("#chart-top-productos"), chartOpts({
        chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{ name: 'Veces Apartado', data: topProd.map(p => p.veces) }],
        xaxis: { categories: topProd.map(p => p.name) },
        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
        dataLabels: { enabled: true },
        colors: ['#8B5CF6'],
    })).render();

    const ingCat = @json($data['productos']['ingresosPorCategoria']);
    new ApexCharts(document.querySelector("#chart-ingresos-categoria"), chartOpts({
        chart: { type: 'donut', height: 320, fontFamily: 'Figtree, sans-serif' },
        series: ingCat.map(c => parseFloat(c.total)),
        labels: ingCat.map(c => c.name),
        legend: { position: 'bottom', fontSize: '11px' },
    })).render();

    // Estado apartados
    const estApt = @json($data['productos']['estadoApartados']);
    const aptColores = { 'active': '#3B82F6', 'completed': '#10B981', 'expired': '#F59E0B', 'cancelled': '#EF4444' };
    const aptLabelsEs = { 'active': 'Activos', 'completed': 'Completados', 'expired': 'Expirados', 'cancelled': 'Cancelados' };
    new ApexCharts(document.querySelector("#chart-estado-apartados"), chartOpts({
        chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{ name: 'Apartados', data: estApt.map(e => e.total) }],
        xaxis: { categories: estApt.map(e => aptLabelsEs[e.status] || e.status) },
        plotOptions: { bar: { borderRadius: 6, distributed: true } },
        colors: estApt.map(e => aptColores[e.status] || '#6B7280'),
        dataLabels: { enabled: true, style: { fontSize: '14px' } },
        legend: { show: false },
    })).render();

    // Tendencia apartados
    const tendApt = @json($data['productos']['tendenciaApartados']);
    new ApexCharts(document.querySelector("#chart-tendencia-apartados"), chartOpts({
        chart: { type: 'line', height: 280, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [
            { name: 'Apartados', data: tendApt.map(t => t.total) },
            { name: 'Ingresos', data: tendApt.map(t => parseFloat(t.ingresos)) }
        ],
        xaxis: { categories: tendApt.map(t => { const [y,mo]=t.mes.split('-'); const n=['','ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic']; return n[parseInt(mo)]+' '+y; }) },
        yaxis: [
            { title: { text: 'Apartados' } },
            { opposite: true, title: { text: 'Ingresos' }, labels: { formatter: v => '$' + Math.round(v).toLocaleString() } }
        ],
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
    })).render();

    // ══════════════════════════════════════════════
    // DASHBOARD 5: KPIs
    // ══════════════════════════════════════════════

    // Composición ingresos (donut)
    new ApexCharts(document.querySelector("#chart-composicion-ingresos"), chartOpts({
        chart: { type: 'donut', height: 300, fontFamily: 'Figtree, sans-serif' },
        series: [{{ $data['kpis']['ingresosServicios'] }}, {{ $data['kpis']['ingresosProductos'] }}],
        labels: ['Servicios', 'Productos'],
        colors: ['#3B82F6', '#8B5CF6'],
        legend: { position: 'bottom' },
    })).render();

    // Citas por estado
    const citasEstado = @json($data['kpis']['citasPorEstado']);
    const citaColores = { 'completed': '#10B981', 'confirmed': '#3B82F6', 'pending': '#F59E0B', 'cancelled': '#EF4444', 'no_show': '#F97316' };
    const citaLabelsEs = { 'completed': 'Completadas', 'confirmed': 'Confirmadas', 'pending': 'Pendientes', 'cancelled': 'Canceladas', 'no_show': 'No asistió' };
    new ApexCharts(document.querySelector("#chart-citas-estado"), chartOpts({
        chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [{ name: 'Citas', data: citasEstado.map(c => c.total) }],
        xaxis: { categories: citasEstado.map(c => citaLabelsEs[c.status] || c.status) },
        plotOptions: { bar: { borderRadius: 6, distributed: true } },
        colors: citasEstado.map(c => citaColores[c.status] || '#6B7280'),
        dataLabels: { enabled: true, style: { fontSize: '13px' } },
        legend: { show: false },
    })).render();

    // Tendencia ingresos KPIs
    const tendKpis = @json($data['kpis']['tendenciaIngresos']);
    new ApexCharts(document.querySelector("#chart-tendencia-kpis"), chartOpts({
        chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'Figtree, sans-serif' },
        series: [
            { name: 'Servicios', data: tendKpis.map(t => parseFloat(t.servicios)) },
            { name: 'Productos', data: tendKpis.map(t => parseFloat(t.productos)) }
        ],
        xaxis: { categories: tendKpis.map(t => { const [y,mo]=t.mes.split('-'); const n=['','ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic']; return n[parseInt(mo)]+' '+y; }) },
        yaxis: { labels: { formatter: v => '$' + Math.round(v).toLocaleString() } },
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
        colors: ['#3B82F6', '#8B5CF6'],
        dataLabels: { enabled: false },
    })).render();
});
</script>
@endpush
@endrole

</x-admin-layout>
