<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Mostrar la lista de servicios.
     */
    public function index()
    {
        // Obtener todos los servicios (incluidos inactivos) ordenados por categoría y nombre
        $services = Service::orderBy('category', 'asc')
                          ->orderBy('name', 'asc')
                          ->get();

        return view('admin.services.index', compact('services'));
    }

    /**
     * Mostrar el formulario de creación.
     */
    public function create()
    {
        // Categorías predefinidas para el select
        $categories = $this->getCategories();

        return view('admin.services.create', compact('categories'));
    }

    /**
     * Guardar un nuevo servicio.
     */
    public function store(Request $request)
    {
        // Validación de los campos del formulario
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:services,name',
            'category'    => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'features'    => 'nullable|string|max:1000',   // Separadas por |
            'duration'    => 'required|integer|min:5|max:480', // 5 min a 8 horas
            'price'       => 'required|numeric|min:0|max:99999.99',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Max 2MB
            'tag'         => 'nullable|string|max:50',
            'is_highlighted' => 'nullable|boolean',
            'status'      => 'required|in:active,inactive',
        ], [
            // Mensajes personalizados en español
            'name.required'     => 'El nombre del servicio es obligatorio.',
            'name.unique'       => 'Ya existe un servicio con ese nombre.',
            'category.required' => 'La categoría es obligatoria.',
            'description.required' => 'La descripción es obligatoria.',
            'duration.required' => 'La duración es obligatoria.',
            'duration.min'      => 'La duración mínima es 5 minutos.',
            'price.required'    => 'El precio es obligatorio.',
            'price.min'         => 'El precio no puede ser negativo.',
            'image.image'       => 'El archivo debe ser una imagen.',
            'image.max'         => 'La imagen no puede pesar más de 2MB.',
            'status.required'   => 'El estado es obligatorio.',
        ]);

        // Generar slug a partir del nombre
        $validated['slug'] = Str::slug($validated['name']);

        // Procesar imagen si se subió una
        if ($request->hasFile('image')) {
            // Guardar en storage/app/public/services/
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // Checkbox: si no se envía, poner false
        $validated['is_highlighted'] = $request->boolean('is_highlighted');

        // Crear el servicio en la base de datos
        Service::create($validated);

        // Redirigir al index con mensaje de éxito
        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Mostrar un servicio específico.
     */
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    /**
     * Mostrar el formulario de edición.
     */
    public function edit(Service $service)
    {
        $categories = $this->getCategories();

        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Actualizar un servicio existente.
     */
    public function update(Request $request, Service $service)
    {
        // Validación — el unique ignora el registro actual
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:services,name,' . $service->id,
            'category'    => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'features'    => 'nullable|string|max:1000',
            'duration'    => 'required|integer|min:5|max:480',
            'price'       => 'required|numeric|min:0|max:99999.99',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'tag'         => 'nullable|string|max:50',
            'is_highlighted' => 'nullable|boolean',
            'status'      => 'required|in:active,inactive',
        ], [
            'name.required'     => 'El nombre del servicio es obligatorio.',
            'name.unique'       => 'Ya existe un servicio con ese nombre.',
            'category.required' => 'La categoría es obligatoria.',
            'description.required' => 'La descripción es obligatoria.',
            'duration.required' => 'La duración es obligatoria.',
            'duration.min'      => 'La duración mínima es 5 minutos.',
            'price.required'    => 'El precio es obligatorio.',
            'price.min'         => 'El precio no puede ser negativo.',
            'image.image'       => 'El archivo debe ser una imagen.',
            'image.max'         => 'La imagen no puede pesar más de 2MB.',
            'status.required'   => 'El estado es obligatorio.',
        ]);

        // Actualizar slug
        $validated['slug'] = Str::slug($validated['name']);

        // Procesar nueva imagen si se subió
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe y es local
            if ($service->image && !Str::startsWith($service->image, ['http://', 'https://'])) {
                Storage::disk('public')->delete($service->image);
            }
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // Checkbox
        $validated['is_highlighted'] = $request->boolean('is_highlighted');

        // Actualizar el servicio
        $service->update($validated);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Eliminar un servicio (soft delete).
     */
    public function destroy(Service $service)
    {
        // Eliminar la imagen si existe y es local
        if ($service->image && !Str::startsWith($service->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($service->image);
        }

        // SoftDelete — no se borra de la BD, solo marca deleted_at
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Servicio eliminado exitosamente.');
    }


    private function getCategories(): array
    {
        return [
            'Cabello',
            'Tratamientos',
            'Estética & Uñas',
            'Paquetes Especiales',
        ];
    }
}
