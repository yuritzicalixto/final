<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Muestra la lista de todos los productos.
     */
    public function index(Request $request)
    {
        // Construir query base con relación de categoría
        $query = Product::with('category');

        // Búsqueda por nombre o marca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrar por categoría
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtrar por estado (active/inactive)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ordenamiento
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['name', 'price', 'stock', 'created_at', 'status'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Obtener productos paginados
        $products = $query->paginate(10)->withQueryString();

        // Obtener categorías para el filtro
        $categories = Category::where('status', 'active')->orderBy('name')->get();

        // Estadísticas para el dashboard
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
            'low_stock' => Product::whereBetween('stock', [1, 5])->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de campos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'stock' => 'required|integer|min:0|max:9999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número válido.',
            'price.min' => 'El precio no puede ser negativo.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser JPEG, PNG, JPG o WebP.',
            'image.max' => 'La imagen no puede superar 2MB.',
        ]);

        // Generar slug único a partir del nombre
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        // Procesar imagen si se subió una
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // Generar nombre único para evitar colisiones
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            // Guardar en storage/app/public/products
            $path = $file->storeAs('products', $filename, 'public');
            $validated['image'] = $path;
        }

        // Crear el producto
        $product = Product::create($validated);

        // Redireccionar con mensaje de éxito
        return redirect()
            ->route('admin.products.index')
            ->with('success', "Producto '{$product->name}' creado exitosamente.");
    }

    /**
     * Muestra los detalles de un producto específico.
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Muestra el formulario para editar un producto existente.
     */
    public function edit(Product $product)
    {
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Actualiza un producto existente.
     */
    public function update(Request $request, Product $product)
    {
        // Validación
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'stock' => 'required|integer|min:0|max:9999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'category_id.required' => 'Debes seleccionar una categoría.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número válido.',
            'price.min' => 'El precio no puede ser negativo.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser JPEG, PNG, JPG o WebP.',
            'image.max' => 'La imagen no puede superar 2MB.',
        ]);

        // Actualizar slug si cambió el nombre
        if ($product->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $counter = 1;

            while (Product::withTrashed()
                         ->where('slug', $slug)
                         ->where('id', '!=', $product->id)
                         ->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        // Manejar la imagen
        // 1. Si se marcó eliminar imagen (usando boolean() para manejar checkbox correctamente)
        if ($request->boolean('remove_image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        }
        // 2. Si se subió una nueva imagen
        elseif ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Guardar nueva imagen
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['image'] = $path;
        }
        // 3. Si no se hizo nada con la imagen, NO incluirla en $validated para mantener la actual
        else {
            unset($validated['image']);
        }

        // Actualizar producto
        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Producto '{$product->name}' actualizado exitosamente.");
    }

    /**
     * Elimina un producto (soft delete).
     */
    public function destroy(Product $product)
    {
        $productName = $product->name;

        // Eliminar imagen si existe
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Soft delete
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Producto '{$productName}' eliminado exitosamente.");
    }

    /**
     * Cambia rápidamente el estado de un producto (AJAX).
     */
    public function toggleStatus(Product $product)
    {
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();

        $statusText = $product->status === 'active' ? 'activado' : 'desactivado';

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $product->status,
                'message' => "Producto {$statusText} exitosamente."
            ]);
        }

        return back()->with('success', "Producto '{$product->name}' {$statusText}.");
    }

    /**
     * Actualiza rápidamente el stock de un producto (AJAX).
     */
    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0|max:9999',
        ]);

        $product->stock = $validated['stock'];
        $product->save();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'stock' => $product->stock,
                'message' => "Stock actualizado a {$product->stock} unidades."
            ]);
        }

        return back()->with('success', "Stock de '{$product->name}' actualizado.");
    }
}
