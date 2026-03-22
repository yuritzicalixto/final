<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // 1. Primero se crea permisos y roles
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        // Usuarios de prueba
        //Admin
        $admin = User::factory()->create([
            'name' => 'Calixto',
            'email' => 'calixtoyualix@gmail.com',
            'phone' => '2712432108',
            'password' => bcrypt('12345678')
        ]);
        $admin->syncRoles(['admin']);

        // Stylist de prueba
        $stylist = User::factory()->create([
            'name' => 'Estilista Demo',
            'email' => 'stylist@gmail.com',
            'phone' => '2712432108',
            'password' => bcrypt('12345678')
        ]);
        $stylist->syncRoles(['stylist']);

        // Client de prueba
        $client = User::factory()->create([
            'name' => 'Cliente Demo',
            'email' => 'client@gmail.com',
            'phone' => '2711000258',
            'password' => bcrypt('12345678')
        ]);
        $client->syncRoles(['client']);

        // $this->call([
        //     PermissionSeeder::class,
        //     RoleSeeder::class,
        // ]);
    }
}
