<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    
    public function run(): void
    {
        // Primero obtenemos las categorías (asegúrate de ejecutar CategorySeeder primero)
        $shampoos = Category::where('slug', 'shampoos')->first();
        $tratamientos = Category::where('slug', 'tratamientos')->first();
        $styling = Category::where('slug', 'styling')->first();

        // Si no existen las categorías, creamos una por defecto
        if (!$shampoos) {
            $shampoos = Category::create([
                'name' => 'Shampoos',
                'slug' => 'shampoos',
                'description' => 'Shampoos profesionales',
                'status' => 'active',
            ]);
        }

        if (!$tratamientos) {
            $tratamientos = Category::create([
                'name' => 'Tratamientos',
                'slug' => 'tratamientos',
                'description' => 'Tratamientos capilares',
                'status' => 'active',
            ]);
        }

        if (!$styling) {
            $styling = Category::create([
                'name' => 'Styling',
                'slug' => 'styling',
                'description' => 'Productos de styling',
                'status' => 'active',
            ]);
        }

        // Array de productos por categoría
        $products = [
            // ========== SHAMPOOS ==========
            [
                'category_id' => $shampoos->id,
                'name' => 'Shampoo Profesional',
                'slug' => 'shampoo-profesional',
                'brand' => 'GGS Pro',
                'description' => 'Limpieza profunda con ingredientes naturales. Ideal para uso diario. 500ml',
                'price' => 350.00,
                'stock' => 25,
                'status' => 'active',
            ],
            [
                'category_id' => $shampoos->id,
                'name' => 'Shampoo Anti-Caspa',
                'slug' => 'shampoo-anti-caspa',
                'brand' => 'GGS Pro',
                'description' => 'Control efectivo de caspa y cuero cabelludo. Fórmula suave y eficaz. 500ml',
                'price' => 380.00,
                'stock' => 18,
                'status' => 'active',
            ],
            [
                'category_id' => $shampoos->id,
                'name' => 'Shampoo Color Protect',
                'slug' => 'shampoo-color-protect',
                'brand' => 'GGS Pro',
                'description' => 'Protege y prolonga el color de tu tinte. Mantiene el brillo y la intensidad. 500ml',
                'price' => 420.00,
                'stock' => 15,
                'status' => 'active',
            ],
            [
                'category_id' => $shampoos->id,
                'name' => 'Shampoo Volumen',
                'slug' => 'shampoo-volumen',
                'brand' => 'GGS Pro',
                'description' => 'Aporta cuerpo y volumen al cabello fino sin apelmazar. 500ml',
                'price' => 360.00,
                'stock' => 20,
                'status' => 'active',
            ],

            // ========== TRATAMIENTOS ==========
            [
                'category_id' => $tratamientos->id,
                'name' => 'Mascarilla Nutritiva',
                'slug' => 'mascarilla-nutritiva',
                'brand' => 'GGS Care',
                'description' => 'Hidratación intensiva para cabello dañado. Restaura la fibra capilar. 250ml',
                'price' => 420.00,
                'stock' => 12,
                'status' => 'active',
            ],
            [
                'category_id' => $tratamientos->id,
                'name' => 'Tratamiento Capilar',
                'slug' => 'tratamiento-capilar',
                'brand' => 'GGS Care',
                'description' => 'Reparación profunda y brillo intenso. Resultados desde la primera aplicación. 200ml',
                'price' => 580.00,
                'stock' => 8,
                'status' => 'active',
            ],
            [
                'category_id' => $tratamientos->id,
                'name' => 'Aceite de Argán',
                'slug' => 'aceite-de-argan',
                'brand' => 'GGS Care',
                'description' => 'Nutrición y brillo sin residuos grasos. 100% puro aceite de argán. 100ml',
                'price' => 450.00,
                'stock' => 10,
                'status' => 'active',
            ],
            [
                'category_id' => $tratamientos->id,
                'name' => 'Sérum Reparador',
                'slug' => 'serum-reparador',
                'brand' => 'GGS Care',
                'description' => 'Sella puntas abiertas y aporta brillo extraordinario. Fórmula profesional. 50ml',
                'price' => 520.00,
                'stock' => 6,
                'status' => 'active',
            ],

            // ========== STYLING ==========
            [
                'category_id' => $styling->id,
                'name' => 'Protector Térmico',
                'slug' => 'protector-termico',
                'brand' => 'GGS Style',
                'description' => 'Protege tu cabello del calor hasta 230°C. Esencial antes de planchar o secar. 200ml',
                'price' => 290.00,
                'stock' => 22,
                'status' => 'active',
            ],
            [
                'category_id' => $styling->id,
                'name' => 'Mousse Volumen',
                'slug' => 'mousse-volumen',
                'brand' => 'GGS Style',
                'description' => 'Crea volumen y textura sin rigidez ni residuos. Fijación media. 300ml',
                'price' => 320.00,
                'stock' => 14,
                'status' => 'active',
            ],
            [
                'category_id' => $styling->id,
                'name' => 'Spray Fijador',
                'slug' => 'spray-fijador',
                'brand' => 'GGS Style',
                'description' => 'Fijación flexible de larga duración. No deja residuos. 400ml',
                'price' => 280.00,
                'stock' => 18,
                'status' => 'active',
            ],
            [
                'category_id' => $styling->id,
                'name' => 'Cera Mate',
                'slug' => 'cera-mate',
                'brand' => 'GGS Style',
                'description' => 'Definición y textura con acabado mate natural. Fijación fuerte. 75g',
                'price' => 260.00,
                'stock' => 16,
                'status' => 'active',
            ],

            // Producto agotado de ejemplo
            [
                'category_id' => $tratamientos->id,
                'name' => 'Keratina Brasileña',
                'slug' => 'keratina-brasilena',
                'brand' => 'GGS Premium',
                'description' => 'Tratamiento profesional de keratina para alisado y reparación profunda. 150ml',
                'price' => 890.00,
                'stock' => 0, // Agotado
                'status' => 'active',
            ],

            // Producto con poco stock
            [
                'category_id' => $shampoos->id,
                'name' => 'Shampoo Platinum',
                'slug' => 'shampoo-platinum',
                'brand' => 'GGS Premium',
                'description' => 'Shampoo matizador para cabellos rubios y decolorados. Elimina tonos amarillos. 500ml',
                'price' => 480.00,
                'stock' => 3, // Poco stock
                'status' => 'active',
            ],
        ];

        // Crear cada producto
        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
