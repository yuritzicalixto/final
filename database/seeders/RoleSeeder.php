<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //
        // Rol Admin - Todos los permisos de administración
        $admin = Role::create(['name' => 'admin']);
        $admin->syncPermissions([
            'access_dashboard',
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
        ]);

        // Rol Stylist
        $stylist = Role::create(['name' => 'stylist']);
        $stylist->syncPermissions([
            'access_dashboard',
            'stylist.appointments.view',
            'stylist.clients.view',
        ]);

        // Rol Client (se asignará por defecto al registrarse)
        $client = Role::create(['name' => 'client']);
        $client->syncPermissions([
            'access_dashboard',
            'client.appointments.create',
            'client.reservations.view',
            'client.cart.use',
        ]);

        // $user = User::find(1);
        // $user->syncRoles([
        //     'admin'
        //     ]);


    }
}
