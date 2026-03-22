<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stylist;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StylistController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra todos los estilistas con su información de usuario.
     */
    public function index()
    {
        // Cargamos la relación 'user' y 'services' para evitar N+1
        $stylists = Stylist::with(['user', 'services'])->latest()->get();

        return view('admin.stylists.index', compact('stylists'));
    }

    /**
     * Show the form for creating a new resource.
     * Muestra el formulario para registrar un nuevo perfil de estilista.
     */
    public function create()
    {
        // Solo usuarios con rol 'stylist' que AÚN NO tengan un perfil de estilista
        $users = User::role('stylist')
            ->whereDoesntHave('stylist') // Evita duplicados
            ->get();

        // Todos los servicios activos para asignar al estilista
        $services = Service::where('status', 'active')->get();

        return view('admin.stylists.create', compact('users', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     * Guarda el nuevo perfil de estilista en la BD.
     */
    public function store(Request $request)
    {
        // Validación de los campos
        $data = $request->validate([
            'user_id'      => 'required|exists:users,id|unique:stylists,user_id',
            'address'      => 'nullable|string|max:255',
            'specialties'  => 'required|string|max:255',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'phone'        => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'status'       => 'required|in:available,unavailable',
            'services'     => 'nullable|array',
            'services.*'   => 'exists:services,id',
        ]);

        // Si se subió una foto, la almacenamos en storage/app/public/stylists
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('stylists', 'public');
        }

        // Creamos el perfil del estilista
        $stylist = Stylist::create($data);

        // Sincronizamos los servicios seleccionados (tabla pivote stylist_service)
        if (isset($data['services'])) {
            $stylist->services()->sync($data['services']);
        }

        // Feedback al usuario
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Estilista creado',
            'text'  => 'El perfil del estilista se ha creado correctamente.',
        ]);

        return redirect()->route('admin.stylists.index');
    }

    /**
     * Display the specified resource.
     * Muestra el detalle de un estilista específico.
     */
    public function show(Stylist $stylist)
    {
        // Cargamos relaciones necesarias para la vista de detalle
        $stylist->load(['user', 'services']);

        return view('admin.stylists.show', compact('stylist'));
    }

    /**
     * Show the form for editing the specified resource.
     * Muestra el formulario de edición con los datos actuales.
     */
    public function edit(Stylist $stylist)
    {
        // Usuarios con rol stylist: el actual + los que no tienen perfil aún
        $users = User::role('stylist')
            ->where(function ($query) use ($stylist) {
                $query->where('id', $stylist->user_id) // El usuario actual del estilista
                      ->orWhereDoesntHave('stylist');   // Usuarios sin perfil
            })
            ->get();

        $services = Service::where('status', 'active')->get();

        // IDs de servicios actualmente asignados (para preseleccionar en el form)
        $assignedServices = $stylist->services->pluck('id')->toArray();

        return view('admin.stylists.edit', compact('stylist', 'users', 'services', 'assignedServices'));
    }

    /**
     * Update the specified resource in storage.
     * Actualiza el perfil del estilista.
     */
    public function update(Request $request, Stylist $stylist)
    {
        $data = $request->validate([
            'user_id'      => 'required|exists:users,id|unique:stylists,user_id,' . $stylist->id,
            'address'      => 'nullable|string|max:255',
            'specialties'  => 'required|string|max:255',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'phone'        => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'status'       => 'required|in:available,unavailable',
            'services'     => 'nullable|array',
            'services.*'   => 'exists:services,id',
        ]);

        // Si se sube nueva foto, eliminamos la anterior y guardamos la nueva
        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($stylist->photo) {
                Storage::disk('public')->delete($stylist->photo);
            }
            $data['photo'] = $request->file('photo')->store('stylists', 'public');
        }

        // Actualizamos los datos del estilista
        $stylist->update($data);

        // Sincronizamos servicios (agrega nuevos, quita los desmarcados)
        if (isset($data['services'])) {
            $stylist->services()->sync($data['services']);
        } else {
            $stylist->services()->detach(); // Si no seleccionó ninguno, limpiamos
        }

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Estilista actualizado',
            'text'  => 'El perfil del estilista se ha actualizado correctamente.',
        ]);

        return redirect()->route('admin.stylists.edit', $stylist);
    }

    /**
     * Remove the specified resource from storage.
     * Elimina el perfil del estilista (no al usuario).
     */
    public function destroy(Stylist $stylist)
    {
        // Eliminamos la foto si existe
        if ($stylist->photo) {
            Storage::disk('public')->delete($stylist->photo);
        }

        // Eliminamos el perfil (los servicios se desvinculan por CASCADE en la tabla pivote)
        $stylist->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Estilista eliminado',
            'text'  => 'El perfil del estilista se ha eliminado correctamente.',
        ]);

        return redirect()->route('admin.stylists.index');
    }
}
