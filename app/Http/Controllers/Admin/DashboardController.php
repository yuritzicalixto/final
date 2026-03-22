<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'servicios'   => $this->dashboardServicios(),
            'estilistas'  => $this->dashboardEstilistas(),
            'clientes'    => $this->dashboardClientes(),
            'productos'   => $this->dashboardProductos(),
            'kpis'        => $this->dashboardKPIs(),
        ];

        return view('admin.dashboard', compact('data'));
    }

    // ─────────────────────────────────────────────
    // DASHBOARD 1 — Análisis de Servicios
    // ─────────────────────────────────────────────
    private function dashboardServicios(): array
    {
        // KPIs
        $totalCitas = DB::table('appointments')->count();
        $completadas = DB::table('appointments')->where('status', 'completed')->count();

        $ingresos = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', 'completed')
            ->sum('services.price');

        $tasaCompletacion = $totalCitas > 0 ? round(($completadas / $totalCitas) * 100, 1) : 0;
        $ticketPromedio = $completadas > 0 ? round($ingresos / $completadas, 2) : 0;

        // Servicios más demandados (barras)
        $serviciosDemandados = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('COUNT(*) as total'))
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->get();

        // Ingresos por servicio (pie)
        $ingresosPorServicio = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', 'completed')
            ->select('services.name', DB::raw('SUM(services.price) as total'))
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->get();

        // Tendencia mensual por categoría (líneas)
        $tendenciaMensual = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select(
                DB::raw("DATE_FORMAT(appointments.date, '%Y-%m') as mes"),
                'services.category',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('mes', 'services.category')
            ->orderBy('mes')
            ->get();

        // Variación mensual de ingresos
        $ingresosMensuales = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', 'completed')
            ->select(
                DB::raw("DATE_FORMAT(appointments.date, '%Y-%m') as mes"),
                DB::raw('SUM(services.price) as total')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return [
            'totalCitas'          => $totalCitas,
            'ingresos'            => $ingresos,
            'tasaCompletacion'    => $tasaCompletacion,
            'ticketPromedio'      => $ticketPromedio,
            'serviciosDemandados' => $serviciosDemandados,
            'ingresosPorServicio' => $ingresosPorServicio,
            'tendenciaMensual'    => $tendenciaMensual,
            'ingresosMensuales'   => $ingresosMensuales,
        ];
    }

    // ─────────────────────────────────────────────
    // DASHBOARD 2 — Productividad de Estilistas
    // ─────────────────────────────────────────────
    private function dashboardEstilistas(): array
    {
        // Citas e ingresos por estilista
        $porEstilista = DB::table('appointments')
            ->join('stylists', 'appointments.stylist_id', '=', 'stylists.id')
            ->join('users', 'stylists.user_id', '=', 'users.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select(
                'users.name as estilista',
                DB::raw('COUNT(*) as total_citas'),
                DB::raw("SUM(CASE WHEN appointments.status = 'completed' THEN 1 ELSE 0 END) as completadas"),
                DB::raw("SUM(CASE WHEN appointments.status = 'completed' THEN services.price ELSE 0 END) as ingresos"),
                DB::raw("SUM(CASE WHEN appointments.status = 'no_show' THEN 1 ELSE 0 END) as no_shows"),
                DB::raw("SUM(CASE WHEN appointments.status = 'cancelled' THEN 1 ELSE 0 END) as canceladas")
            )
            ->groupBy('stylists.id', 'users.name')
            ->orderByDesc('ingresos')
            ->get()
            ->map(function ($e) {
                $e->tasa_completacion = $e->total_citas > 0 ? round(($e->completadas / $e->total_citas) * 100, 1) : 0;
                $e->tasa_no_show = $e->total_citas > 0 ? round(($e->no_shows / $e->total_citas) * 100, 1) : 0;
                return $e;
            });

        // Evolución mensual por estilista
        $evolucionEstilistas = DB::table('appointments')
            ->join('stylists', 'appointments.stylist_id', '=', 'stylists.id')
            ->join('users', 'stylists.user_id', '=', 'users.id')
            ->where('appointments.status', 'completed')
            ->select(
                DB::raw("DATE_FORMAT(appointments.date, '%Y-%m') as mes"),
                'users.name as estilista',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('mes', 'stylists.id', 'users.name')
            ->orderBy('mes')
            ->get();

        return [
            'porEstilista'        => $porEstilista,
            'evolucionEstilistas' => $evolucionEstilistas,
        ];
    }

    // ─────────────────────────────────────────────
    // DASHBOARD 3 — Análisis de Clientes (RFM)
    // ─────────────────────────────────────────────
    private function dashboardClientes(): array
    {
        $hoy = Carbon::today();

        // Datos RFM por cliente
        $clientes = DB::table('appointments')
            ->join('users', 'appointments.client_id', '=', 'users.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', 'completed')
            ->select(
                'users.id',
                'users.name',
                DB::raw('MAX(appointments.date) as ultima_visita'),
                DB::raw('COUNT(*) as total_visitas'),
                DB::raw('SUM(services.price) as total_gastado'),
                DB::raw('ROUND(AVG(services.price), 2) as promedio_visita')
            )
            ->groupBy('users.id', 'users.name')
            ->get()
            ->map(function ($c) use ($hoy) {
                $c->dias_sin_visitar = Carbon::parse($c->ultima_visita)->diffInDays($hoy);

                // R Score
                if ($c->dias_sin_visitar <= 7) $c->r = 5;
                elseif ($c->dias_sin_visitar <= 15) $c->r = 4;
                elseif ($c->dias_sin_visitar <= 30) $c->r = 3;
                elseif ($c->dias_sin_visitar <= 60) $c->r = 2;
                else $c->r = 1;

                // F Score
                if ($c->total_visitas >= 100) $c->f = 5;
                elseif ($c->total_visitas >= 70) $c->f = 4;
                elseif ($c->total_visitas >= 50) $c->f = 3;
                elseif ($c->total_visitas >= 35) $c->f = 2;
                else $c->f = 1;

                // M Score
                if ($c->total_gastado >= 50000) $c->m = 5;
                elseif ($c->total_gastado >= 35000) $c->m = 4;
                elseif ($c->total_gastado >= 25000) $c->m = 3;
                elseif ($c->total_gastado >= 15000) $c->m = 2;
                else $c->m = 1;

                // RFM combinado
                $c->rfm_score = round($c->r * 0.25 + $c->f * 0.40 + $c->m * 0.35, 1);

                // Segmento
                if ($c->rfm_score >= 4.0) $c->segmento = 'VIP';
                elseif ($c->rfm_score >= 3.0) $c->segmento = 'Leal';
                elseif ($c->rfm_score >= 2.0) $c->segmento = 'Regular';
                elseif ($c->rfm_score >= 1.5) $c->segmento = 'En Riesgo';
                else $c->segmento = 'Perdido';

                return $c;
            })
            ->sortByDesc('rfm_score')
            ->values();

        // Conteo por segmento
        $segmentos = $clientes->groupBy('segmento')->map->count();

        // Ingresos por segmento
        $ingresosPorSegmento = $clientes->groupBy('segmento')->map(function ($grupo) {
            return round($grupo->sum('total_gastado'), 2);
        });

        // Servicio preferido (distribución)
        $servicioPref = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', 'completed')
            ->select('services.name', DB::raw('COUNT(*) as total'))
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->get();

        return [
            'clientes'            => $clientes,
            'totalClientes'       => $clientes->count(),
            'clientesVIP'         => $segmentos->get('VIP', 0),
            'clientesRiesgo'      => $segmentos->get('En Riesgo', 0) + $segmentos->get('Perdido', 0),
            'promedioVisitas'     => round($clientes->avg('total_visitas'), 1),
            'segmentos'           => $segmentos,
            'ingresosPorSegmento' => $ingresosPorSegmento,
            'servicioPref'        => $servicioPref,
        ];
    }

    // ─────────────────────────────────────────────
    // DASHBOARD 4 — Venta de Productos
    // ─────────────────────────────────────────────
    private function dashboardProductos(): array
    {
        // KPIs de apartados
        $totalApartados = DB::table('reservations')->count();
        $completados = DB::table('reservations')->where('status', 'completed')->count();
        $ingresosApartados = DB::table('reservations')->where('status', 'completed')->sum('total');
        $tasaConversion = $totalApartados > 0 ? round(($completados / $totalApartados) * 100, 1) : 0;

        // Productos con stock bajo
        $stockBajo = DB::table('products')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->where('stock', '<', 5)
            ->count();

        $sinStock = DB::table('products')
            ->where('status', 'active')
            ->where('stock', '<=', 0)
            ->count();

        $valorInventario = DB::table('products')
            ->where('status', 'active')
            ->selectRaw('SUM(price * stock) as total')
            ->value('total') ?? 0;

        // Top productos más apartados
        $topProductos = DB::table('reservation_items')
            ->join('products', 'reservation_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('COUNT(*) as veces'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('veces')
            ->limit(10)
            ->get();

        // Ingresos por categoría de producto
        $ingresosPorCategoria = DB::table('reservation_items')
            ->join('products', 'reservation_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('reservations', 'reservation_items.reservation_id', '=', 'reservations.id')
            ->where('reservations.status', 'completed')
            ->select('categories.name', DB::raw('SUM(reservation_items.subtotal) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        // Inventario crítico (stock < 10)
        $inventarioCritico = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.status', 'active')
            ->where('products.stock', '<', 10)
            ->select('products.name', 'products.brand', 'products.stock', 'products.price', 'categories.name as categoria')
            ->orderBy('products.stock')
            ->get();

        // Apartados por estado
        $estadoApartados = DB::table('reservations')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // Tendencia mensual de apartados
        $tendenciaApartados = DB::table('reservations')
            ->select(
                DB::raw("DATE_FORMAT(reservation_date, '%Y-%m') as mes"),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN total ELSE 0 END) as ingresos")
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return [
            'totalApartados'      => $totalApartados,
            'ingresosApartados'   => $ingresosApartados,
            'tasaConversion'      => $tasaConversion,
            'stockBajo'           => $stockBajo,
            'sinStock'            => $sinStock,
            'valorInventario'     => $valorInventario,
            'topProductos'        => $topProductos,
            'ingresosPorCategoria'=> $ingresosPorCategoria,
            'inventarioCritico'   => $inventarioCritico,
            'estadoApartados'     => $estadoApartados,
            'tendenciaApartados'  => $tendenciaApartados,
        ];
    }

    // ─────────────────────────────────────────────
    // DASHBOARD 5 — KPIs Principales
    // ─────────────────────────────────────────────
    private function dashboardKPIs(): array
    {
        $ingresosServicios = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', 'completed')
            ->sum('services.price');

        $ingresosProductos = DB::table('reservations')
            ->where('status', 'completed')
            ->sum('total');

        $ingresoTotal = $ingresosServicios + $ingresosProductos;
        $pctServicios = $ingresoTotal > 0 ? round(($ingresosServicios / $ingresoTotal) * 100, 1) : 0;
        $pctProductos = $ingresoTotal > 0 ? round(($ingresosProductos / $ingresoTotal) * 100, 1) : 0;

        $totalCitas = DB::table('appointments')->count();
        $completadas = DB::table('appointments')->where('status', 'completed')->count();
        $tasaCompletacion = $totalCitas > 0 ? round(($completadas / $totalCitas) * 100, 1) : 0;

        $noShows = DB::table('appointments')->where('status', 'no_show')->count();
        $tasaNoShow = $totalCitas > 0 ? round(($noShows / $totalCitas) * 100, 1) : 0;

        // Días laborales con citas
        $diasConCitas = DB::table('appointments')
            ->distinct('date')
            ->count('date');
        $citasPorDia = $diasConCitas > 0 ? round($totalCitas / $diasConCitas, 1) : 0;
        $ingresoPorDia = $diasConCitas > 0 ? round($ingresosServicios / $diasConCitas, 2) : 0;

        $clientesActivos = DB::table('appointments')->distinct('client_id')->count('client_id');
        $ticketPromedio = $completadas > 0 ? round($ingresosServicios / $completadas, 2) : 0;

        // Citas por estado
        $citasPorEstado = DB::table('appointments')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        // Tendencia de ingresos mensuales (servicios + productos)
        $tendenciaIngresos = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', 'completed')
            ->select(
                DB::raw("DATE_FORMAT(appointments.date, '%Y-%m') as mes"),
                DB::raw('SUM(services.price) as servicios')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $tendenciaProductos = DB::table('reservations')
            ->where('status', 'completed')
            ->select(
                DB::raw("DATE_FORMAT(reservation_date, '%Y-%m') as mes"),
                DB::raw('SUM(total) as productos')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        // Combinar ambas tendencias
        $tendenciaIngresos = $tendenciaIngresos->map(function ($item) use ($tendenciaProductos) {
            $item->productos = $tendenciaProductos->has($item->mes)
                ? $tendenciaProductos[$item->mes]->productos
                : 0;
            return $item;
        });

        // Stock bajo para alerta
        $stockBajo = DB::table('products')
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->where('stock', '<', 5)
            ->count();

        return [
            'ingresoTotal'       => $ingresoTotal,
            'ingresosServicios'  => $ingresosServicios,
            'ingresosProductos'  => $ingresosProductos,
            'pctServicios'       => $pctServicios,
            'pctProductos'       => $pctProductos,
            'totalCitas'         => $totalCitas,
            'tasaCompletacion'   => $tasaCompletacion,
            'citasPorDia'        => $citasPorDia,
            'ingresoPorDia'      => $ingresoPorDia,
            'clientesActivos'    => $clientesActivos,
            'ticketPromedio'     => $ticketPromedio,
            'tasaNoShow'         => $tasaNoShow,
            'stockBajo'          => $stockBajo,
            'citasPorEstado'     => $citasPorEstado,
            'tendenciaIngresos'  => $tendenciaIngresos,
        ];
    }
}
