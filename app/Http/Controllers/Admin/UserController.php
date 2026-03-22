<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //recuperamos todo el listado de roles
        $roles = Role::all();
        //
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // Reglas de validación
        // Estos valores validados se recuperan en un array
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'password' => 'required|min:6|confirmed',
            'roles' => 'nullable|array',
        ]);

        // Se crea el usuario
        $user = User::create($data);

        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }
        // Una vez creado el usuario
        // Nos redirecciona a esta vista
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
        //Reglas de validación
        //Estos valores validados se recuperan en un array
        $data= $request->validate([
            'name'=> 'required',
            'email'=> 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'password'=> 'nullable|min:6|confirmed',
            'roles'=> 'nullable|array',
        ]);

        $user-> name = $data['name'];
        $user-> email = $data['email'];
        $user -> phone = $data['phone'];

        if($data['password']) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();

        //Preguntar si tiene roles, y sincronizarlo con los ya existentes
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        } else{
            $user->roles()->detach();
        }


        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado',
            'text' => 'El usuario se ha actualizado correctamente.',
        ]);

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
        //Eliminar al usuario
        $user->delete();

        session()->flash('swal', [
            'icon'=> 'success',
            'title'=> 'Usuario eliminado',
            'text'=> 'El usuario se ha eliminado correctamente',
        ]);

        return redirect()->route('admin.users.index');
    }
}
