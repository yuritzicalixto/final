<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use App\Models\Appointment;
use App\Models\Stylist;
use Carbon\Carbon;

class AdminAppointmentTable extends DataTableComponent
{
    protected $model = Appointment::class;

    // ──────────────────────────────────────────────
    // Configuración general
    // ──────────────────────────────────────────────
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('date', 'desc');

        $this->setPerPageAccepted([10, 25, 50]);

        $this->setColumnSelectDisabled();
        $this->setSortingPillsDisabled();

        // Importante: mantener las relaciones cargadas en la query
        $this->setEagerLoadAllRelationsEnabled();
    }


    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Appointment::query()
            ->select('appointments.*')
            ->with(['client', 'stylist.user', 'service']);
    }

    // ──────────────────────────────────────────────
    // Filtros: fecha, estilista y estado
    // ──────────────────────────────────────────────
    public function filters(): array
    {
        $stylistOptions = Stylist::with('user')
            ->get()
            ->mapWithKeys(fn ($s) => [$s->id => $s->user->name ?? "Estilista #{$s->id}"])
            ->prepend('Todos', '')
            ->toArray();

        return [
            DateFilter::make('Desde')
                ->filter(function ($builder, string $value) {
                    $builder->whereDate('appointments.date', '>=', $value);
                }),

            DateFilter::make('Hasta')
                ->filter(function ($builder, string $value) {
                    $builder->whereDate('appointments.date', '<=', $value);
                }),

            SelectFilter::make('Estilista')
                ->options($stylistOptions)
                ->filter(function ($builder, string $value) {
                    $builder->where('appointments.stylist_id', $value);
                }),

            SelectFilter::make('Estado')
                ->options([
                    ''          => 'Todos',
                    'pending'   => 'Pendiente',
                    'confirmed' => 'Confirmada',
                    'completed' => 'Completada',
                    'cancelled' => 'Cancelada',
                    'no_show'   => 'No asistió',
                ])
                ->filter(function ($builder, string $value) {
                    $builder->where('appointments.status', $value);
                }),
        ];
    }

    // ──────────────────────────────────────────────
    // Mapa de estilos por estado (centralizado)
    // ──────────────────────────────────────────────
    private function getStatusBadge(string $status): string
    {
        $config = match ($status) {
            'pending'   => ['label' => 'Pendiente',   'classes' => 'bg-amber-100 text-amber-700 ring-amber-600/20'],
            'confirmed' => ['label' => 'Confirmada',  'classes' => 'bg-blue-100 text-blue-700 ring-blue-600/20'],
            'completed' => ['label' => 'Completada',  'classes' => 'bg-green-100 text-green-700 ring-green-600/20'],
            'cancelled' => ['label' => 'Cancelada',   'classes' => 'bg-red-100 text-red-700 ring-red-600/20'],
            'no_show'   => ['label' => 'No asistió',  'classes' => 'bg-gray-100 text-gray-600 ring-gray-500/20'],
            default     => ['label' => $status,        'classes' => 'bg-gray-100 text-gray-600 ring-gray-500/20'],
        };

        return "<span class=\"inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset {$config['classes']}\">{$config['label']}</span>";
    }

    // ──────────────────────────────────────────────
    // Definición de columnas
    //
    // Clave: Para columnas de relaciones, se usa la
    // notación de punto ('client.name') en lugar de
    // label() con callbacks. Esto le indica a Rappasoft
    // que resuelva la relación internamente mediante
    // JOINs, garantizando que el dato siempre se muestre.
    // ──────────────────────────────────────────────
    public function columns(): array
    {
        return [
            // Column::make('ID', 'id')
            //     ->sortable(),

            // Fecha: columna directa del modelo, format() recibe el valor del campo
            Column::make('Fecha', 'date')
                ->sortable()
                ->format(function ($value, $row) {
                    $date = Carbon::parse($value)->translatedFormat('d M Y');

                    // Construir el rango de hora desde los campos directos
                    // en lugar del accessor, para evitar problemas con el tipo de $row
                    $start = Carbon::parse($row->start_time)->format('H:i');
                    $end   = Carbon::parse($row->end_time)->format('H:i');

                    return "{$date}<br><span class=\"text-xs text-gray-500\">{$start} - {$end}</span>";
                })
                ->html(),

            // Cliente: notación de punto para relación directa
            // Rappasoft resuelve 'client.name' → appointments.client_id → users.name
            Column::make('Cliente', 'client.name')
                ->sortable()
                ->searchable(),

            // Estilista: relación anidada stylist → user → name
            // Rappasoft puede resolver relaciones anidadas con notación de punto
            Column::make('Estilista', 'stylist.user.name')
                ->sortable(),

            // Servicio: relación directa
            Column::make('Servicio', 'service.name')
                ->sortable()
                ->searchable(),

            // Estado: campo directo, format() para renderizar HTML del badge
            Column::make('Estado', 'status')
                ->sortable()
                ->format(fn ($value) => $this->getStatusBadge($value))
                ->html(),

            // Acciones: label() es seguro aquí porque solo usa $row->id
            // que siempre está disponible (es la PK de la tabla principal)
            Column::make('Acciones')
                ->label(function ($row) {
                    $showUrl = route('admin.appointments.show', $row->id);

                    return <<<HTML
                        <a href="{$showUrl}"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-sky-600 hover:bg-sky-50 transition-colors"
                           title="Ver detalle">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </a>
                    HTML;
                })
                ->html()
                ->unclickable(),
        ];
    }
}
