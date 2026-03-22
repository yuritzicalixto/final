@php
    $links= [
        // ADMIN
    [
        'icon' => 'fa-solid fa-gauge',
        'name' => 'Dashboard',
        'route' => route ('admin.dashboard'),
        'active' => request()->routeIs('admin.dashboard'),
        'can' => ['access_dashboard']
    ],
    [
        'icon' => 'fa-solid fa-users',
        'name' => 'Usuarios',
        'route' => route ('admin.users.index'),
        'active' => request()->routeIs('admin.users.*'),
        'can' => ['users.manage']
    ],
    [
        'icon' => 'fa-solid fa-shop',
        'name' => 'Servicios',
        'route' => route ('admin.services.index'),
        'active' => request()->routeIs('admin.services.*'),
        'can' => ['services.manage']
    ],
    [
        'icon' => 'fa-solid fa-cubes-stacked',
        'name' => 'Productos',
        'route' => route ('admin.products.index'),
        'active' => request()->routeIs('admin.products.*'),
        'can' => ['products.manage'] //
    ],
    [
        'icon' => 'fa-solid fa-users-gear',
        'name' => 'Estilistas',
        'route' => route ('admin.stylists.index'),
        'active' => request()->routeIs('admin.stylists.*'),
        'can' => ['stylists.manage']
    ],
    [
        'icon' => 'fa-solid fa-calendar-check',
        'name' => 'Citas Generales',
        'route' => route('admin.appointments.index'),
        'active' => request()->routeIs('admin.appointments.*'),
        'can' => ['appointments.manage']
    ],
    [
        'icon' => 'fa-solid fa-cart-flatbed',
        'name' => 'Apartados',
        'route' => route ('admin.reservations.index'),
        'active' => request()->routeIs('admin.reservations.*'),
        'can' => ['reservations.manage']
    ],
    [
        'icon' => 'fa-solid fa-bell',
        'name' => 'Notificaciones',
        'route' => route('admin.notifications.index'),
        'active' => request()->routeIs('admin.notifications.*'),
        'can' => ['notifications.manage']
    ],
    // [
    //     'icon' => 'fa-solid fa-percent',
    //     'name' => 'Promociones',
    //     'route' => route ('admin.dashboard'),
    //     'active' => request()->routeIs('admin.dashboard')
    // ],
    // [
    //     'icon' => 'fa-solid fa-folder-tree',
    //     'name' => 'Reportes (P.BI)',
    //     'route' => route ('admin.dashboard'),
    //     'active' => request()->routeIs('admin.dashboard')
    // ],

    // ESTILISTA
    [
        'icon' => 'fa-solid fa-image-portrait',
        'name' => 'Mis Citas',
        'route' => route ('stylist.appointments.index'),
        'active' => request()->routeIs('stylist.appointments.*'),
        'can' => ['stylist.appointments.view']
    ],
    // [
    //     'icon' => 'fa-solid fa-people-group',
    //     'name' => 'Mis Clientes',
    //     'route' => route ('stylist.clients.index'),
    //     'active' => request()->routeIs('stylist.clients.*'),
    //     'can' => ['stylist.clients.view']
    // ],

    // CLIENTE
    [
        'icon' => 'fa-solid fa-address-book',
        'name' => 'Agendar Cita',
        'route' => route ('client.appointments.index'),
        'active' => request()->routeIs('client.appointments.*'),
        'can' => ['client.appointments.create']
    ],
    [
        'icon' => 'fa-solid fa-store',
        'name' => 'Mis Apartados',
        'route' => route ('client.reservations.index'),
        'active' => request()->routeIs('client.reservations.*'),
        'can' => ['client.reservations.view']
    ],
];
@endphp

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-[100dvh] pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
    :class="{
        'translate-x-0 ease-out': sidebarOpen,
        '-translate-x-full ease-in': !sidebarOpen
    }"
    aria-label="Sidebar">
    <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
        <ul class="space-y-2 font-medium">
{{--ITERANDO ENLACES--}}
             @foreach ($links as $link)
                @canany($link['can'] ?? [null])

                    <li>
                        <a href="{{ $link['route'] }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-100' : ''}}">

                            <span class="inline-flex w-6 h-6 justify-center items-center">
                                <i class=" {{$link['icon']}} text-gray-500"></i>
                            </span>

                            <span class="ms-2">
                                {{$link['name']}}
                            </span>
                        </a>
                    </li>
                @endcanany
            @endforeach
        </ul>
    </div>
</aside>
