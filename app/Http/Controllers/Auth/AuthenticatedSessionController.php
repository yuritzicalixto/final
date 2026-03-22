<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create(Request $request)
    {
        // PRIORIDAD 1: Si viene un redirect_to explícito (desde el botón "Agendar" en servicios),
        // lo guardamos como intended. Esto tiene prioridad sobre todo lo demás.
        if ($request->has('redirect_to')) {
            $request->session()->put('url.intended', $request->redirect_to);

        // PRIORIDAD 2: Si no hay redirect_to ni intended previo,
        // guardamos la URL anterior (comportamiento original).
        } elseif (!$request->session()->has('url.intended')) {
            $previousUrl = url()->previous();

            if (
                $previousUrl !== route('login') &&
                $previousUrl !== route('register')
            ) {
                $request->session()->put('url.intended', $previousUrl);
            }
        }

        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            // redirect()->intended() busca 'url.intended' en la sesión.
            // Si el usuario venía de "Agendar", lo lleva al formulario de cita.
            // Si venía de otra página, lo lleva allá.
            // Si no hay nada guardado, lo lleva al frontend principal.
            return redirect()->intended(route('sitio.index'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput();
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sitio.index');
    }
}
