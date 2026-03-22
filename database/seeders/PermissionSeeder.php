<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();



        //
        $permissions = [
            'access_dashboard',
            // ADMIN
            'appointments.manage',
            'users.manage',
            'services.manage',
            'products.manage',
            'stylists.manage',
            'reservations.manage',
            'notifications.manage',
            'promotions.manage',
            'reports.manage',
            'roles.manage',
            'permissions.manage',

            // STYLIST
            'stylist.appointments.view',
            'stylist.clients.view',

            // CLIENT
            'client.appointments.create',
            'client.reservations.view',
            'client.cart.use',
        ];

        foreach ($permissions as $permission) {

            Permission::create([
                'name' => $permission,
            ]);
        }
    }
}
