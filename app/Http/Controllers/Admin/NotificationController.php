<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotificationLog;
use App\Models\User;
use App\Notifications\PromotionalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\PushSubscription;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    /**
     * Dashboard de notificaciones con historial.
     */
    public function index()
    {
        // Estadísticas
        $stats = [
            'subscribed_users'    => PushSubscription::distinct('subscribable_id')->count(),
            'sent_today'          => PushNotificationLog::whereDate('created_at', today())->sum('recipients_count'),
            'sent_this_month'     => PushNotificationLog::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->sum('recipients_count'),
            'total_sent'          => PushNotificationLog::count(),
        ];

        // Historial de notificaciones enviadas
        $notifications = PushNotificationLog::with('sender')
            ->latest()
            ->paginate(15);

        return view('admin.notifications.index', compact('stats', 'notifications'));
    }

    /**
     * Formulario para crear notificación promocional.
     */
    public function create()
    {
        // Clientes que tienen al menos una suscripción push activa
        $subscribedClients = User::role('client')
            ->whereHas('pushSubscriptions')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.notifications.create', compact('subscribedClients'));
    }

    /**
     * Enviar la notificación.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:100',
            'body'      => 'required|string|max:255',
            'url'       => 'nullable|string|max:255',
            'audience'  => 'required|in:all,selected',
            'users'     => 'required_if:audience,selected|array',
            'users.*'   => 'exists:users,id',
        ]);

        // Determinar destinatarios
        if ($validated['audience'] === 'all') {
            $users = User::role('client')
                ->whereHas('pushSubscriptions')
                ->get();
        } else {
            $users = User::whereIn('id', $validated['users'] ?? [])
                ->whereHas('pushSubscriptions')
                ->get();
        }

        if ($users->isEmpty()) {
            return back()->with('error', 'No hay usuarios con notificaciones push activas.');
        }

        // Enviar la notificación
        Notification::send(
            $users,
            new PromotionalNotification(
                $validated['title'],
                $validated['body'],
                $validated['url'] ?? null,
            )
        );

        // Registrar en el log
        PushNotificationLog::create([
            'title'            => $validated['title'],
            'body'             => $validated['body'],
            'url'              => $validated['url'] ?? null,
            'type'             => 'promotional',
            'audience'         => $validated['audience'],
            'recipients_count' => $users->count(),
            'sent_by' => Auth::id(),
            'recipient_ids'    => $validated['audience'] === 'selected'
                                    ? $users->pluck('id')->toArray()
                                    : null,
        ]);

        return redirect()->route('admin.notifications.index')
            ->with('success', "Notificación enviada a {$users->count()} usuario(s).");
    }

    /**
     * Ver detalle de una notificación enviada.
     */
    public function show(PushNotificationLog $notification)
    {
        $notification->load('sender');

        // Si fue a usuarios seleccionados, cargar sus datos
        $recipients = null;
        if ($notification->recipient_ids) {
            $recipients = User::whereIn('id', $notification->recipient_ids)
                ->get(['id', 'name', 'email']);
        }

        return view('admin.notifications.show', compact('notification', 'recipients'));
    }
}
