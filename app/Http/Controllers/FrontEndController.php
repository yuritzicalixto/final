<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    function index(){
        return view('sitio.pages.index');//busca la vista que va a utilizar
    }

    function galeria(){
        return view('sitio.pages.galeria');//busca la vista que va a utilizar
    }

    function nosotros(){
        return view('sitio.pages.nosotros');//busca la vista que va a utilizar
    }

    function productos(){

        // Obtener todas las categorías activas que tienen al menos un producto activo
        // Esto usa eager loading para cargar los productos de cada categoría eficientemente
        $categories = Category::active()
            ->whereHas('products', function ($query) {
                // Solo categorías que tengan productos activos
                $query->where('status', 'active');
            })
            ->with(['products' => function ($query) {
                // Cargar solo productos activos, ordenados por nombre
                $query->where('status', 'active')
                      ->orderBy('name', 'asc');
            }])
            ->orderBy('name', 'asc')
            ->get();

        // Pasar las categorías con sus productos a la vista
        return view('sitio.pages.productos', compact('categories'));//busca la vista que va a utilizar
    }

    // PRODUCTOS
    public function productoDetalle(Product $product)
    {
        // Verificar que el producto esté activo
        if ($product->status !== 'active') {
            abort(404);
        }

        // Cargar productos relacionados de la misma categoría
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('sitio.pages.producto-detalle', compact('product', 'relatedProducts'));
    }

    /**
     * API endpoint para obtener productos (útil para el carrito con JS).
     *
     * Retorna productos en formato JSON para uso con AJAX/Fetch.
     */
    public function apiProductos(Request $request)
    {
        $query = Product::active()->with('category');

        // Filtrar por categoría si se especifica
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'brand' => $product->brand,
                'description' => $product->description,
                'price' => (float) $product->price,
                'formatted_price' => $product->formatted_price,
                'stock' => $product->stock,
                'is_available' => $product->is_available,
                'image' => $product->image_url,
                'category' => $product->category->name ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    /**
     * API endpoint para obtener un producto específico por ID.
     */
    public function apiProducto(Product $product)
    {
        if ($product->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Producto no disponible',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'brand' => $product->brand,
                'description' => $product->description,
                'price' => (float) $product->price,
                'formatted_price' => $product->formatted_price,
                'stock' => $product->stock,
                'is_available' => $product->is_available,
                'image' => $product->image_url,
                'category' => $product->category->name ?? null,
            ],
        ]);
    }
    // PRODUCTOS

    function servicios(){
        // Obtener solo servicios activos, ordenados por nombre
        // y agruparlos por el campo 'category'
        $servicesByCategory = Service::active()
            ->orderBy('name', 'asc')
            ->get()
            ->groupBy('category');

            // colección agrupada a la vista
        return view('sitio.pages.servicios', compact('servicesByCategory'));
    }

    function register(){
        return view('register');
    }


    function login(){
        return redirect()->route('login');
    }
}
