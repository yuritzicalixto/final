// ============================================================
// public/sw.js — Service Worker para Push Notifications
// Guillermo Salón — Sistema de Gestión
// ============================================================

// Al instalarse, toma control inmediatamente sin esperar
// a que se cierren otras pestañas del sitio.
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

// Al activarse, reclama todos los clientes (pestañas abiertas)
// para que empiecen a usar este SW inmediatamente.
self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

// ============================================================
// EVENTO PUSH — Se dispara cuando el servidor envía un mensaje
// ============================================================
self.addEventListener('push', (event) => {
    // Verificar que tenemos permiso para mostrar notificaciones
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    // Intentar parsear el payload como JSON
    let data = {};
    if (event.data) {
        try {
            data = event.data.json();
        } catch (e) {
            // Si no es JSON válido, usar el texto plano como body
            data = {
                title: 'Guillermo Salón',
                body: event.data.text()
            };
        }
    }

    // Configurar las opciones de la notificación
    const options = {
        body: data.body || '',
        icon: data.icon || '/img/icon-192.png',
        badge: data.badge || '/img/badge-72.png',
        tag: data.tag || 'general',
        renotify: !!data.renotify,
        requireInteraction: !!data.requireInteraction,
        data: data.data || {},
        // Las acciones son botones que aparecen en la notificación
        actions: data.actions || [],
    };

    // IMPORTANTE para iOS Safari: showNotification() debe llamarse
    // de forma SÍNCRONA dentro de event.waitUntil().
    // NO pongas await/fetch/async antes de esta línea.
    event.waitUntil(
        self.registration.showNotification(
            data.title || 'Guillermo Salón',
            options
        )
    );
});

// ============================================================
// CLIC EN NOTIFICACIÓN — Abre la URL correspondiente
// ============================================================
self.addEventListener('notificationclick', (event) => {
    // Cerrar la notificación
    event.notification.close();

    // Obtener la URL a abrir (viene del campo 'data.url' del payload)
    const targetUrl = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then((clientList) => {
            // Si ya hay una pestaña abierta con esa URL, enfocarla
            for (const client of clientList) {
                try {
                    const url = new URL(client.url);
                    if (url.pathname === targetUrl) {
                        return client.focus();
                    }
                } catch (e) { /* ignorar URLs inválidas */ }
            }

            // Si hay alguna pestaña del sitio, navegar a la URL
            for (const client of clientList) {
                if ('navigate' in client) {
                    return client.navigate(targetUrl).then(c => c?.focus());
                }
            }

            // Si no hay ninguna pestaña, abrir una nueva
            return clients.openWindow(targetUrl);
        })
    );
});

// ============================================================
// RENOVACIÓN DE SUSCRIPCIÓN (solo Firefox la soporta por ahora)
// ============================================================
self.addEventListener('pushsubscriptionchange', (event) => {
    event.waitUntil(
        self.registration.pushManager
            .subscribe(event.oldSubscription?.options ?? { userVisibleOnly: true })
            .then((newSub) => {
                return fetch('/push-subscriptions/refresh', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        old_endpoint: event.oldSubscription?.endpoint ?? null,
                        endpoint: newSub.endpoint,
                        public_key: newSub.toJSON().keys?.p256dh ?? null,
                        auth_token: newSub.toJSON().keys?.auth ?? null,
                    }),
                });
            })
    );
});
