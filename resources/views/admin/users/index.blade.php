<x-admin-layout :breadcrumbs="[
    [
        'name'=> 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name'=> 'Usuarios',
    ],
]">

    {{-- Botón de acción mejorado en el slot del header --}}
    <x-slot name="action">
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo usuario
        </a>
    </x-slot>

    {{-- Tabla de usuarios --}}
    @livewire('user-table')

    {{-- Script para la confirmación de eliminación con SweetAlert --}}
    @push('js')
        <script>
            // Escucha el evento que dispara el componente Livewire
            Livewire.on('confirm-user-delete', (data) => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡No podrás revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '¡Sí, bórralo!',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Despacha de vuelta al componente Livewire para ejecutar la eliminación
                        Livewire.dispatch('deleteUserConfirmed', { userId: data.userId });
                    }
                });
            });
        </script>
    @endpush

</x-admin-layout>
