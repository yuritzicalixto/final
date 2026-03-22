<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function create(Request $request)
    {
        // Misma lógica: guardar URL de origen
        if (!$request->session()->has('url.intended')) {
            $previousUrl = url()->previous();
            if (
                $previousUrl !== route('login') &&
                $previousUrl !== route('register')
            ) {
                $request->session()->put('url.intended', $previousUrl);
            }
        }

        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Asignar el rol 'client' por defecto
        $user->assignRole('client');

        Auth::login($user);

        // Redirigir al frontend (o a la URL de origen si existe)
        return redirect()->intended(route('sitio.index'));
    }
}
