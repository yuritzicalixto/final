<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    // =====================================================
    // CONFIGURACIÓN DE DATOS DEMO
    // =====================================================

    /**
     * Período de datos a generar.
     * 7 meses de historial para buenos dashboards.
     */
    const START_DATE = '2025-08-01';
    const END_DATE   = '2026-02-22';

    /**
     * Horario del negocio.
     */
    const OPEN_HOUR  = 10;
    const CLOSE_HOUR = 17;

    /**
     * Servicios del salón con pesos de popularidad.
     * El peso determina qué tan frecuentemente se agenda cada servicio.
     * Un peso mayor = más citas de ese servicio = más popular en los dashboards.
     */
    private array $servicesData = [
        // [nombre, slug, categoría, duración_min, precio, peso_popularidad, features, tag]
        ['Corte de Cabello Dama',   'corte-cabello-dama',     'Cortes',       45,  250,  30, 'Consulta de estilo|Lavado|Corte profesional|Secado', 'Popular'],
        ['Corte de Cabello Caballero','corte-cabello-caballero','Cortes',      30,  150,  22, 'Corte clásico o moderno|Lavado|Secado', null],
        ['Tinte Completo',          'tinte-completo',          'Color',       120,  800,  12, 'Consulta de color|Aplicación completa|Lavado|Secado', null],
        ['Mechas / Balayage',       'mechas-balayage',         'Color',       150, 1200,   8, 'Técnica personalizada|Matizado|Tratamiento post-color|Secado', 'Premium'],
        ['Alaciado Permanente',     'alaciado-permanente',     'Tratamientos',180, 1500,   4, 'Diagnóstico capilar|Aplicación de keratina|Planchado|Sellado', null],
        ['Tratamiento Hidratante',  'tratamiento-hidratante',  'Tratamientos', 60,  400,  10, 'Diagnóstico capilar|Mascarilla nutritiva|Vapor|Secado', null],
        ['Peinado para Evento',     'peinado-evento',          'Styling',      60,  500,   5, 'Consulta de estilo|Lavado|Peinado elaborado|Fijación', null],
        ['Manicure',                'manicure',                'Uñas',         45,  200,   6, 'Limado|Cutícula|Esmaltado|Hidratación', null],
        ['Pedicure',                'pedicure',                'Uñas',         60,  250,   4, 'Exfoliación|Limado|Cutícula|Esmaltado', null],
        ['Maquillaje Profesional',  'maquillaje-profesional',  'Maquillaje',   60,  600,   3, 'Preparación de piel|Maquillaje completo|Fijación', null],
        ['Barba y Bigote',          'barba-bigote',            'Cortes',       30,  100,   8, 'Perfilado|Rasurado|Toalla caliente', null],
        ['Keratina Brasileña',      'keratina-brasilena',      'Tratamientos',120, 1000,   5, 'Lavado profundo|Aplicación de keratina|Planchado|Sellado', 'Signature'],
    ];

    /**
     * Categorías de productos.
     */
    private array $categoriesData = [
        'Shampoo y Acondicionador',
        'Tratamientos Capilares',
        'Styling y Acabado',
        'Coloración',
        'Cuidado de Uñas',
        'Accesorios',
    ];

    /**
     * Productos del salón.
     * [nombre, categoría_index, marca, precio, stock_inicial]
     */
    private array $productsData = [
        ['Shampoo Reparación Extrema',     0, 'L\'Oréal Professionnel', 320, 25],
        ['Acondicionador Hydra Rescue',     0, 'L\'Oréal Professionnel', 350, 20],
        ['Shampoo Control Grasa',           0, 'Kérastase',              420, 15],
        ['Acondicionador Nutritive',        0, 'Kérastase',              450, 12],
        ['Mascarilla Capilar Intensa',      1, 'Moroccanoil',            580, 18],
        ['Aceite de Argán',                 1, 'Moroccanoil',            490, 22],
        ['Sérum Reparador de Puntas',       1, 'Redken',                360, 16],
        ['Ampolleta Regeneradora',          1, 'L\'Oréal Professionnel', 180, 30],
        ['Mousse Volumen',                  2, 'Schwarzkopf',            320, 14],
        ['Spray Fijador Extra Fuerte',      2, 'Schwarzkopf',            280, 20],
        ['Cera Moldeadora Mate',            2, 'American Crew',          250, 18],
        ['Gel Fijación Fuerte',             2, 'Moco de Gorila',         85,  35],
        ['Kit Tinte Semi-Permanente',       3, 'Wella',                  350, 10],
        ['Polvo Decolorante',               3, 'Wella',                  280, 12],
        ['Oxidante 20 Volúmenes',           3, 'L\'Oréal',              120, 25],
        ['Esmalte Gel UV',                  4, 'OPI',                    180, 20],
        ['Acetona Profesional 500ml',       4, 'Gelish',                 95,  28],
        ['Kit Lima y Bloque',               4, 'Nail Tek',               65,  40],
        ['Cepillo Térmico Redondo',         5, 'Olivia Garden',          280, 15],
        ['Pinzas Profesionales (set x6)',   5, 'BaByliss',               150, 22],
    ];

    /**
     * Nombres mexicanos para clientes demo.
     * Nombres realistas para que se vean bien en los dashboards.
     */
    private array $clientNames = [
        'María González', 'Ana López', 'Laura Martínez', 'Sofía Hernández',
        'Fernanda García', 'Valentina Rodríguez', 'Camila Sánchez', 'Isabella Ramírez',
        'Daniela Torres', 'Andrea Flores', 'Gabriela Díaz', 'Patricia Morales',
        'Carolina Ortiz', 'Alejandra Reyes', 'Mariana Cruz', 'Natalia Jiménez',
        'Paulina Vargas', 'Renata Castillo', 'Ximena Romero', 'Lucía Mendoza',
        'Diana Herrera', 'Elena Aguilar', 'Rosa Peña', 'Carmen Medina',
        'Teresa Guerrero', 'Claudia Ríos', 'Sandra Álvarez', 'Adriana Delgado',
        'Roberto Pérez', 'Carlos Ruiz', 'Miguel Ángel Torres', 'Jorge Hernández',
        'Luis Castro', 'Fernando Moreno', 'Alejandro Vega', 'David Contreras',
        'Ricardo Luna', 'Eduardo Figueroa', 'Javier Navarro', 'Héctor Domínguez',
    ];

    /**
     * Estilistas demo con sus especialidades.
     * [nombre, especialidades, servicios_indices (del array $servicesData)]
     */
    private array $stylistsData = [
        ['Estilista Demo',      'Cortes, Color, Styling',           [0, 1, 2, 3, 5, 6, 10, 11]], // El que ya existe (user_id=2)
        ['Gabriela Montoya',    'Color, Tratamientos, Keratina',    [0, 2, 3, 4, 5, 6, 11]],
        ['Ricardo Vásquez',     'Cortes Caballero, Barba, Styling', [0, 1, 5, 10, 11]],
        ['Alondra Mejía',       'Uñas, Maquillaje, Peinados',      [0, 6, 7, 8, 9]],
    ];

    // =====================================================
    // MÉTODO PRINCIPAL
    // =====================================================

    public function run(): void
    {
        $this->command->info('Iniciando generación de datos demo para Power BI...');
        $this->command->newLine();

        // Paso 1: Servicios
        $serviceIds = $this->createServices();
        $this->command->info("{$serviceIds->count()} servicios creados/verificados");

        // Paso 2: Categorías y productos
        $categoryIds = $this->createCategories();
        $productIds = $this->createProducts($categoryIds);
        $this->command->info("{$productIds->count()} productos creados/verificados");

        // Paso 3: Clientes demo
        $clientIds = $this->createDemoClients();
        $this->command->info("{$clientIds->count()} clientes demo creados");

        // Paso 4: Estilistas y asignación de servicios
        $stylistIds = $this->createDemoStylists();
        $this->assignServicesToStylists($stylistIds, $serviceIds);
        $this->command->info("{$stylistIds->count()} estilistas configurados con servicios");

        // Paso 5: Generar citas (el grueso de los datos)
        $appointmentCount = $this->generateAppointments($serviceIds, $clientIds, $stylistIds);
        $this->command->info("{$appointmentCount} citas generadas (Ago 2025 – Feb 2026)");

        // Paso 6: Generar apartados de productos
        $reservationCount = $this->generateReservations($clientIds, $productIds);
        $this->command->info("{$reservationCount} apartados generados con sus items");

        $this->command->newLine();
        $this->command->info('¡Datos demo generados exitosamente! Conecta Power BI a tu base de datos.');
    }

    // =====================================================
    // PASO 1: SERVICIOS
    // =====================================================

    private function createServices(): \Illuminate\Support\Collection
    {
        $ids = collect();

        foreach ($this->servicesData as $s) {
            // Usar firstOrCreate por nombre para no duplicar si ya existen
            $existing = DB::table('services')->where('name', $s[0])->first();

            if ($existing) {
                $ids->push($existing->id);
            } else {
                $id = DB::table('services')->insertGetId([
                    'name'           => $s[0],
                    'slug'           => $s[1],
                    'category'       => $s[2],
                    'description'    => "Servicio profesional de {$s[0]} en Guillermo Gutiérrez Salón.",
                    'duration'       => $s[3],
                    'price'          => $s[4],
                    'features'       => $s[6],
                    'tag'            => $s[7],
                    'is_highlighted' => $s[7] === 'Signature' ? 1 : 0,
                    'image'          => null,
                    'status'         => 'active',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
                $ids->push($id);
            }
        }

        return $ids;
    }

    // =====================================================
    // PASO 2: CATEGORÍAS Y PRODUCTOS
    // =====================================================

    private function createCategories(): \Illuminate\Support\Collection
    {
        $ids = collect();

        foreach ($this->categoriesData as $name) {
            $existing = DB::table('categories')->where('name', $name)->first();

            if ($existing) {
                $ids->push($existing->id);
            } else {
                $id = DB::table('categories')->insertGetId([
                    'name'       => $name,
                    'slug'       => \Illuminate\Support\Str::slug($name),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $ids->push($id);
            }
        }

        return $ids;
    }

    private function createProducts(\Illuminate\Support\Collection $categoryIds): \Illuminate\Support\Collection
    {
        $ids = collect();

        foreach ($this->productsData as $p) {
            $existing = DB::table('products')->where('name', $p[0])->whereNull('deleted_at')->first();

            if ($existing) {
                $ids->push($existing->id);
            } else {
                $id = DB::table('products')->insertGetId([
                    'category_id' => $categoryIds[$p[1]],
                    'name'        => $p[0],
                    'slug'        => Str::slug($p[0]),
                    'brand'       => $p[2],
                    'description' => "Producto profesional {$p[0]} de {$p[2]}.",
                    'price'       => $p[3],
                    'stock'       => $p[4],
                    'image'       => null,
                    'status'      => 'active',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                $ids->push($id);
            }
        }

        return $ids;
    }

    // =====================================================
    // PASO 3: CLIENTES DEMO
    // =====================================================

    private function createDemoClients(): \Illuminate\Support\Collection
    {
        $clientRole = \Spatie\Permission\Models\Role::findByName('client', 'web');
        $ids = collect();

        foreach ($this->clientNames as $index => $name) {
            // Generar email único basado en el nombre
            $email = Str::slug($name, '.') . '.demo@salon.test';

            $existing = User::where('email', $email)->first();
            if ($existing) {
                $ids->push($existing->id);
                continue;
            }

            $user = User::create([
                'name'              => $name,
                'email'             => $email,
                'phone'             => '271' . str_pad(rand(1000000, 9999999), 7, '0'),
                'email_verified_at' => now(),
                'password'          => Hash::make('12345678'),
                'status'            => 'active',
            ]);

            $user->assignRole($clientRole);
            $ids->push($user->id);
        }

        return $ids;
    }

    // =====================================================
    // PASO 4: ESTILISTAS
    // =====================================================

    private function createDemoStylists(): \Illuminate\Support\Collection
    {
        $stylistRole = \Spatie\Permission\Models\Role::findByName('stylist', 'web');
        $ids = collect();

        foreach ($this->stylistsData as $index => $s) {
            if ($index === 0) {
                // El primer estilista ya existe como user_id=2 (Estilista Demo del DatabaseSeeder)
                $user = User::find(2);
                if (!$user) {
                    // Si no existe, crearlo
                    $user = User::create([
                        'name'              => $s[0],
                        'email'             => 'stylist@gmail.com',
                        'phone'             => '2712432108',
                        'email_verified_at' => now(),
                        'password'          => Hash::make('12345678'),
                        'status'            => 'active',
                    ]);
                    $user->assignRole($stylistRole);
                }

                // Crear o verificar registro en tabla stylists
                $stylist = DB::table('stylists')->where('user_id', $user->id)->first();
                if (!$stylist) {
                    $stylistId = DB::table('stylists')->insertGetId([
                        'user_id'     => $user->id,
                        'specialties' => $s[1],
                        'phone'       => '2712432108',
                        'status'      => 'available',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                    $ids->push($stylistId);
                } else {
                    $ids->push($stylist->id);
                }
            } else {
                // Crear nuevos estilistas
                $email = Str::slug($s[0], '.') . '@salon.test';

                $user = User::where('email', $email)->first();
                if (!$user) {
                    $user = User::create([
                        'name'              => $s[0],
                        'email'             => $email,
                        'phone'             => '271' . str_pad(rand(1000000, 9999999), 7, '0'),
                        'email_verified_at' => now(),
                        'password'          => Hash::make('12345678'),
                        'status'            => 'active',
                    ]);
                    $user->assignRole($stylistRole);
                }

                $stylist = DB::table('stylists')->where('user_id', $user->id)->first();
                if (!$stylist) {
                    $stylistId = DB::table('stylists')->insertGetId([
                        'user_id'     => $user->id,
                        'specialties' => $s[1],
                        'phone'       => '271' . str_pad(rand(1000000, 9999999), 7, '0'),
                        'status'      => 'available',
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                    $ids->push($stylistId);
                } else {
                    $ids->push($stylist->id);
                }
            }
        }

        return $ids;
    }

    /**
     * Asignar servicios a cada estilista según sus especialidades.
     * Usa la tabla pivot stylist_service.
     */
    private function assignServicesToStylists(
        \Illuminate\Support\Collection $stylistIds,
        \Illuminate\Support\Collection $serviceIds
    ): void {
        foreach ($this->stylistsData as $index => $s) {
            $stylistId = $stylistIds[$index];

            foreach ($s[2] as $serviceIndex) {
                $serviceId = $serviceIds[$serviceIndex];

                // Verificar que no exista ya la relación
                $exists = DB::table('stylist_service')
                    ->where('stylist_id', $stylistId)
                    ->where('service_id', $serviceId)
                    ->exists();

                if (!$exists) {
                    DB::table('stylist_service')->insert([
                        'stylist_id' => $stylistId,
                        'service_id' => $serviceId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    // =====================================================
    // PASO 5: GENERAR CITAS
    // =====================================================

    /**
     * Genera citas realistas para cada estilista, cada día laborable.
     *
     * Lógica clave:
     * - Para cada día hábil (L-S), cada estilista genera una agenda.
     * - Se avanza slot por slot (10:00 → 17:00) sin traslapes.
     * - La probabilidad de ocupar un slot varía por día y mes.
     * - El status depende de si la fecha es pasada, presente o futura.
     * - Diciembre tiene un pico estacional (temporada navideña).
     */
    private function generateAppointments(
        \Illuminate\Support\Collection $serviceIds,
        \Illuminate\Support\Collection $clientIds,
        \Illuminate\Support\Collection $stylistIds
    ): int {
        $count = 0;
        $startDate = Carbon::parse(self::START_DATE);
        $endDate   = Carbon::parse(self::END_DATE);
        $today     = Carbon::today();

        // Pre-cargar datos de servicios para eficiencia
        $services = DB::table('services')->whereIn('id', $serviceIds->toArray())->get()->keyBy('id');

        // Construir mapa de servicios por estilista (para saber qué puede ofrecer cada uno)
        $stylistServiceMap = [];
        foreach ($this->stylistsData as $index => $s) {
            $stylistServiceMap[$stylistIds[$index]] = collect($s[2])
                ->map(fn ($si) => $serviceIds[$si])
                ->toArray();
        }

        // Pesos de popularidad de servicios (para distribución ponderada)
        $serviceWeights = [];
        foreach ($this->servicesData as $index => $s) {
            $serviceWeights[$serviceIds[$index]] = $s[5]; // peso de popularidad
        }

        // Categorizar clientes para análisis RFM interesante:
        // - VIP (primeros 8): visitan muy frecuentemente
        // - Regulares (siguientes 15): visitan con frecuencia media
        // - Ocasionales (resto): visitan poco
        $vipClients     = $clientIds->slice(0, 8)->values();
        $regularClients = $clientIds->slice(8, 15)->values();
        $casualClients  = $clientIds->slice(23)->values();

        // Batch insert para rendimiento
        $batch = [];
        $batchSize = 100;

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            // Saltar domingos — el salón no abre
            if ($current->isSunday()) {
                $current->addDay();
                continue;
            }

            $isSaturday = $current->isSaturday();
            $month = $current->month;

            // Factor de ocupación: más citas en sábado y en diciembre
            $baseOccupancy = $isSaturday ? 0.88 : 0.72;

            // Ajuste mensual: crecimiento gradual + pico en diciembre
            $monthFactor = match ($month) {
                8  => 0.80,  // Agosto — inicio, menos clientela
                9  => 0.85,  // Septiembre
                10 => 0.90,  // Octubre
                11 => 0.95,  // Noviembre
                12 => 1.15,  // Diciembre — pico navideño
                1  => 0.82,  // Enero — baja post-fiestas
                2  => 0.92,  // Febrero — recuperación
                default => 0.90,
            };

            $occupancy = min(0.95, $baseOccupancy * $monthFactor);

            // Generar agenda para cada estilista en este día
            foreach ($stylistIds as $stylistId) {
                $currentTime = Carbon::createFromTime(self::OPEN_HOUR, 0);
                $closeTime   = Carbon::createFromTime(self::CLOSE_HOUR, 0);

                $availableServices = $stylistServiceMap[$stylistId] ?? [];
                if (empty($availableServices)) continue;

                while ($currentTime->copy()->addMinutes(30)->lte($closeTime)) {
                    // ¿Se ocupa este slot?
                    if (mt_rand(1, 100) > ($occupancy * 100)) {
                        // Slot vacío — avanzar 30 min
                        $currentTime->addMinutes(30);
                        continue;
                    }

                    // Elegir servicio ponderado por popularidad
                    $serviceId = $this->weightedRandom($availableServices, $serviceWeights);
                    $service   = $services[$serviceId];

                    // Verificar que el servicio cabe antes del cierre
                    $endTime = $currentTime->copy()->addMinutes($service->duration);
                    if ($endTime->gt($closeTime)) {
                        // Intentar con un servicio más corto (30 min)
                        $shortServices = collect($availableServices)->filter(
                            fn ($sid) => $services[$sid]->duration <= $closeTime->diffInMinutes($currentTime)
                        );
                        if ($shortServices->isEmpty()) break;

                        $serviceId = $this->weightedRandom($shortServices->toArray(), $serviceWeights);
                        $service   = $services[$serviceId];
                        $endTime   = $currentTime->copy()->addMinutes($service->duration);
                    }

                    // Elegir cliente según categoría (VIP más frecuente)
                    $clientId = $this->pickClient($vipClients, $regularClients, $casualClients);

                    // Determinar status según la fecha
                    $status = $this->determineAppointmentStatus($current, $today);

                    // Datos de cancelación si el status es 'cancelled'
                    $cancellationReason = null;
                    $cancelledBy = null;
                    $cancelledAt = null;
                    if ($status === 'cancelled') {
                        $cancellationReason = collect([
                            'Surgió un imprevisto personal',
                            'Cambio de planes de último momento',
                            'Problema de salud',
                            'No pude conseguir transporte',
                            'Reagendaré para otra fecha',
                        ])->random();
                        $cancelledBy = mt_rand(0, 1) ? 'client' : 'admin';
                        $cancelledAt = $current->copy()->subDays(rand(1, 3))->setHour(rand(8, 18));
                    }

                    $batch[] = [
                        'client_id'           => $clientId,
                        'stylist_id'          => $stylistId,
                        'service_id'          => $serviceId,
                        'reservation_id'      => null,
                        'date'                => $current->format('Y-m-d'),
                        'start_time'          => $currentTime->format('H:i:s'),
                        'end_time'            => $endTime->format('H:i:s'),
                        'status'              => $status,
                        'notes'               => mt_rand(1, 10) <= 2 ? 'Cliente frecuente, atención preferencial.' : null,
                        'cancellation_reason' => $cancellationReason,
                        'cancelled_by'        => $cancelledBy,
                        'cancelled_at'        => $cancelledAt,
                        'reminder_sent'       => $status !== 'pending' ? 1 : 0,
                        'created_at'          => $current->copy()->subDays(rand(1, 7))->setHour(rand(9, 20)),
                        'updated_at'          => $current->copy(),
                    ];

                    $count++;

                    // Insertar en lotes para rendimiento
                    if (count($batch) >= $batchSize) {
                        DB::table('appointments')->insert($batch);
                        $batch = [];
                    }

                    // Avanzar el tiempo por la duración del servicio
                    $currentTime->addMinutes($service->duration);
                }
            }

            $current->addDay();
        }

        // Insertar registros restantes
        if (!empty($batch)) {
            DB::table('appointments')->insert($batch);
        }

        return $count;
    }

    /**
     * Determina el status de una cita basado en si la fecha ya pasó o no.
     *
     * Citas pasadas: mayormente completadas (el negocio funciona bien).
     * Citas futuras: pendientes o confirmadas (aún no ocurren).
     * Citas de hoy: mezcla de todos los estados.
     */
    private function determineAppointmentStatus(Carbon $date, Carbon $today): string
    {
        if ($date->lt($today)) {
            // Fecha pasada — distribución realista de un salón funcional
            $rand = mt_rand(1, 100);
            if ($rand <= 68) return 'completed';   // 68% completadas
            if ($rand <= 80) return 'confirmed';    // 12% confirmadas (olvidaron marcar como completada)
            if ($rand <= 90) return 'cancelled';    // 10% canceladas
            if ($rand <= 97) return 'no_show';      //  7% no asistió
            return 'pending';                       //  3% pendientes viejas
        }

        if ($date->eq($today)) {
            // Hoy — mezcla
            $rand = mt_rand(1, 100);
            if ($rand <= 35) return 'completed';
            if ($rand <= 75) return 'confirmed';
            return 'pending';
        }

        // Fecha futura
        $rand = mt_rand(1, 100);
        if ($rand <= 55) return 'pending';
        return 'confirmed';
    }

    /**
     * Elige un cliente ponderado por categoría.
     * Los VIP aparecen más frecuentemente que los ocasionales,
     * creando patrones interesantes para el análisis RFM.
     */
    private function pickClient(
        \Illuminate\Support\Collection $vip,
        \Illuminate\Support\Collection $regular,
        \Illuminate\Support\Collection $casual
    ): int {
        $rand = mt_rand(1, 100);

        if ($rand <= 40) {
            // 40% de probabilidad de que sea un VIP (genera alta frecuencia)
            return $vip->random();
        }

        if ($rand <= 78) {
            // 38% regulares
            return $regular->random();
        }

        // 22% ocasionales
        return $casual->random();
    }

    // =====================================================
    // PASO 6: GENERAR APARTADOS (RESERVACIONES)
    // =====================================================

    /**
     * Genera reservaciones de productos a lo largo del período.
     *
     * Cada reservación tiene 1-3 productos (como en el flujo real del carrito).
     * La distribución de status sigue la misma lógica temporal que las citas.
     */
    private function generateReservations(
        \Illuminate\Support\Collection $clientIds,
        \Illuminate\Support\Collection $productIds
    ): int {
        $count = 0;
        $startDate = Carbon::parse(self::START_DATE);
        $endDate   = Carbon::parse(self::END_DATE);
        $today     = Carbon::today();

        // Pre-cargar precios de productos
        $products = DB::table('products')->whereIn('id', $productIds->toArray())->get()->keyBy('id');

        // Reservación counter para generar números únicos
        $reservationCounter = DB::table('reservations')->count();

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            // Generar 0-3 apartados por semana (solo en días hábiles)
            if ($current->isSunday()) {
                $current->addDay();
                continue;
            }

            // Solo crear apartados algunos días (no todos los días)
            $dailyChance = match ($current->month) {
                12 => 45,  // Diciembre más compras
                11 => 35,
                1  => 20,  // Enero menos compras
                default => 30,
            };

            if (mt_rand(1, 100) > $dailyChance) {
                $current->addDay();
                continue;
            }

            // 1-2 apartados este día
            $numReservations = mt_rand(1, 2);

            for ($i = 0; $i < $numReservations; $i++) {
                $reservationCounter++;
                $clientId = $clientIds->random();
                $reservationDate = $current->copy();
                $expirationDate  = $reservationDate->copy()->addDays(7);

                // Determinar status según la fecha
                $status = $this->determineReservationStatus($expirationDate, $today);

                // Generar items (1-3 productos)
                $numItems = mt_rand(1, 3);
                $selectedProducts = $productIds->random(min($numItems, $productIds->count()));
                if (!is_iterable($selectedProducts)) {
                    $selectedProducts = collect([$selectedProducts]);
                }

                $total = 0;
                $items = [];

                foreach ($selectedProducts as $productId) {
                    $product  = $products[$productId];
                    $quantity = 1; // Cantidad fija = 1 (como en el carrito actual)
                    $subtotal = $product->price * $quantity;
                    $total   += $subtotal;

                    $items[] = [
                        'product_id' => $productId,
                        'quantity'   => $quantity,
                        'unit_price' => $product->price,
                        'subtotal'   => $subtotal,
                        'created_at' => $reservationDate,
                        'updated_at' => $reservationDate,
                    ];
                }

                // Número de apartado con formato del modelo: APT-AÑO-00001
                $reservationNumber = sprintf(
                    'APT-%d-%05d',
                    $reservationDate->year,
                    $reservationCounter
                );

                // Insertar la reservación
                $reservationId = DB::table('reservations')->insertGetId([
                    'client_id'          => $clientId,
                    'reservation_number' => $reservationNumber,
                    'reservation_date'   => $reservationDate->format('Y-m-d'),
                    'expiration_date'    => $expirationDate->format('Y-m-d'),
                    'total'              => $total,
                    'status'             => $status,
                    'notes'              => null,
                    'created_at'         => $reservationDate,
                    'updated_at'         => $status === 'active' ? $reservationDate : $expirationDate->copy()->subDays(rand(0, 3)),
                ]);

                // Insertar los items de la reservación
                foreach ($items as &$item) {
                    $item['reservation_id'] = $reservationId;
                }
                DB::table('reservation_items')->insert($items);

                $count++;
            }

            $current->addDay();
        }

        return $count;
    }

    /**
     * Determina el status de un apartado basado en su fecha de expiración.
     */
    private function determineReservationStatus(Carbon $expirationDate, Carbon $today): string
    {
        if ($expirationDate->lt($today)) {
            // Ya venció — distribución de estados finales
            $rand = mt_rand(1, 100);
            if ($rand <= 55) return 'completed';   // 55% recogidos a tiempo
            if ($rand <= 80) return 'expired';      // 25% vencidos sin recoger
            return 'cancelled';                     // 20% cancelados
        }

        // Aún vigente
        if ($expirationDate->diffInDays($today) <= 2) {
            // Próximo a vencer
            $rand = mt_rand(1, 100);
            if ($rand <= 30) return 'completed';
            return 'active';
        }

        return 'active';
    }

    // =====================================================
    // UTILIDADES
    // =====================================================

    /**
     * Selección aleatoria ponderada.
     * Elige un elemento del array basado en los pesos asignados.
     * Elementos con mayor peso tienen más probabilidad de ser elegidos.
     */
    private function weightedRandom(array $items, array $weights): mixed
    {
        $totalWeight = 0;
        foreach ($items as $item) {
            $totalWeight += $weights[$item] ?? 1;
        }

        $rand = mt_rand(1, $totalWeight);
        $cumulative = 0;

        foreach ($items as $item) {
            $cumulative += $weights[$item] ?? 1;
            if ($rand <= $cumulative) {
                return $item;
            }
        }

        return end($items);
    }
}
