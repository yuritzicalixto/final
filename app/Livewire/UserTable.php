<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Livewire\Attributes\On;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    // ──────────────────────────────────────────────
    // Configuración general de la tabla
    // ──────────────────────────────────────────────
    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id', 'asc');

        // Paginación
        $this->setPerPageAccepted([10, 25, 50]);

        // Desactivar elementos visuales innecesarios
        $this->setColumnSelectDisabled();
        $this->setSortingPillsDisabled();
    }

    // ──────────────────────────────────────────────
    // Builder: eager load de roles para evitar N+1
    // ──────────────────────────────────────────────
    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return User::query()->with('roles');
    }

    // ──────────────────────────────────────────────
    // Mapa de colores por rol (centralizado)
    // ──────────────────────────────────────────────
    private function getRoleBadgeClasses(string $roleName): string
    {
        return match ($roleName) {
            'admin'   => 'bg-red-100 text-red-700 ring-red-600/20',
            'stylist' => 'bg-purple-100 text-purple-700 ring-purple-600/20',
            'client'  => 'bg-sky-100 text-sky-700 ring-sky-600/20',
            default   => 'bg-gray-100 text-gray-600 ring-gray-500/20',
        };
    }

    // ──────────────────────────────────────────────
    // Definición de columnas
    // ──────────────────────────────────────────────
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Correo', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'phone')
                ->sortable()
                ->searchable(),

            // Columna de roles con badges de colores
            Column::make('Roles')
                ->label(function ($row) {
                    if ($row->roles->isEmpty()) {
                        return '<span class="text-sm italic text-gray-400">Sin rol</span>';
                    }

                    return $row->roles->map(function ($role) {
                        $classes = $this->getRoleBadgeClasses($role->name);
                        $name    = ucfirst($role->name);

                        return "<span class=\"inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset {$classes}\">{$name}</span>";
                    })->implode(' ');
                })
                ->html(),

            // Columna de acciones: ver, editar, eliminar
            Column::make('Acciones')
                ->label(function ($row) {
                    $showUrl = route('admin.users.show', $row);
                    $editUrl = route('admin.users.edit', $row);

                    return <<<HTML
                        <div class="flex items-center space-x-1">
                            <a href="{$showUrl}"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-sky-600 hover:bg-sky-50 transition-colors"
                               title="Ver">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                            <a href="{$editUrl}"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-amber-50 transition-colors"
                               title="Editar">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <button wire:click="confirmDelete({$row->id})"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-colors"
                                    title="Eliminar">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </div>
                    HTML;
                })
                ->html()
                ->unclickable(),
        ];
    }

    // ──────────────────────────────────────────────
    // Eliminación con confirmación SweetAlert
    // ──────────────────────────────────────────────

    /**
     * Paso 1: Dispara el evento al navegador para mostrar SweetAlert
     */
    public function confirmDelete(int $userId): void
    {
        $this->dispatch('confirm-user-delete', userId: $userId);
    }

    /**
     * Paso 2: Recibe la confirmación desde JS y ejecuta la eliminación
     */
    #[On('deleteUserConfirmed')]
    public function deleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->delete();

        // Notificación de éxito
        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => 'Usuario eliminado',
            'text'  => 'El usuario se ha eliminado correctamente.',
        ]);
    }
}
