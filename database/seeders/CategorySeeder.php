<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    
    public function run(): void
    {
        // Array de categorías que coinciden con el frontend actual
        $categories = [
            [
                'name' => 'Shampoos',
                'slug' => 'shampoos',
                'description' => 'Shampoos profesionales para todo tipo de cabello. Limpieza profunda con ingredientes de alta calidad.',
                'status' => 'active',
            ],
            [
                'name' => 'Tratamientos',
                'slug' => 'tratamientos',
                'description' => 'Tratamientos capilares para hidratar, reparar y fortalecer tu cabello desde la raíz hasta las puntas.',
                'status' => 'active',
            ],
            [
                'name' => 'Styling',
                'slug' => 'styling',
                'description' => 'Productos de styling para crear y mantener tu peinado favorito con un acabado profesional.',
                'status' => 'active',
            ],
            [
                'name' => 'Acondicionadores',
                'slug' => 'acondicionadores',
                'description' => 'Acondicionadores para suavizar, desenredar y proteger tu cabello.',
                'status' => 'active',
            ],
            [
                'name' => 'Tintes y Color',
                'slug' => 'tintes-color',
                'description' => 'Productos profesionales de coloración para un color vibrante y duradero.',
                'status' => 'active',
            ],
            [
                'name' => 'Accesorios',
                'slug' => 'accesorios',
                'description' => 'Herramientas y accesorios profesionales para el cuidado del cabello.',
                'status' => 'active',
            ],
        ];

        // Crear cada categoría
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
