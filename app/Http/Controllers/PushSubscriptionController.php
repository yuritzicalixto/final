<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /**
     * Guardar o actualizar una suscripción push.
     * Se llama desde el JS del frontend cada vez que se carga una página.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint'         => 'required|url:https|max:500',
            'public_key'       => 'nullable|string',
            'auth_token'       => 'nullable|string',
            'content_encoding' => 'nullable|string|in:aesgcm,aes128gcm',
        ]);

        // El trait HasPushSubscriptions provee este método.
        // Hace upsert: si el endpoint ya existe, lo actualiza.
        $request->user()->updatePushSubscription(
            $validated['endpoint'],
            $validated['public_key'] ?? null,
            $validated['auth_token'] ?? null,
            $validated['content_encoding'] ?? null,
        );

        return response()->json(['success' => true]);
    }

    /**
     * Eliminar una suscripción push (cuando el usuario desactiva notificaciones).
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|url:https',
        ]);

        $request->user()->deletePushSubscription($validated['endpoint']);

        return response()->json(['success' => true]);
    }
}
