{{-- ============================================================
     Push Notifications — Script de suscripción
     Incluir en layouts DESPUÉS de que el usuario esté autenticado.
     ============================================================ --}}
@auth
<script>
(function() {
    'use strict';

    // ---- Verificar soporte del navegador ----
    if (!('serviceWorker' in navigator) || !('PushManager' in window) || !('Notification' in window)) {
        console.warn('Push notifications no soportadas en este navegador.');
        return;
    }

    // ---- Configuración ----
    const VAPID_PUBLIC_KEY = document.querySelector('meta[name="vapid-public-key"]')?.content;
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!VAPID_PUBLIC_KEY) {
        console.warn('VAPID public key no encontrada en meta tags.');
        return;
    }

    // ---- Convertir clave VAPID de Base64URL a Uint8Array ----
    // El Push API requiere la clave en este formato binario.
    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    // ---- Enviar suscripción al servidor Laravel ----
    async function sendSubscriptionToServer(subscription) {
        const key = subscription.getKey('p256dh');
        const auth = subscription.getKey('auth');
        const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];

        const response = await fetch('/push-subscriptions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({
                endpoint: subscription.endpoint,
                public_key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                auth_token: auth ? btoa(String.fromCharCode.apply(null, new Uint8Array(auth))) : null,
                content_encoding: contentEncoding,
            }),
        });

        if (!response.ok) {
            throw new Error('Error al guardar suscripción: ' + response.status);
        }
    }

    // ---- Suscribir al usuario a push notifications ----
    async function subscribeToPush() {
        try {
            const registration = await navigator.serviceWorker.ready;

            // En iOS, pushManager solo existe si el sitio está instalado como PWA
            if (!registration.pushManager) {
                console.info('PushManager no disponible. En iOS, instala la app desde "Agregar a pantalla de inicio".');
                return;
            }

            // Verificar si ya hay una suscripción activa
            let subscription = await registration.pushManager.getSubscription();

            if (!subscription) {
                // Pedir permiso y crear nueva suscripción
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    console.info('Permiso de notificaciones denegado.');
                    return;
                }

                subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
                });
            }

            // Enviar/actualizar la suscripción en el servidor
            // Esto se hace en cada carga de página porque Chrome NO dispara
            // el evento pushsubscriptionchange cuando la suscripción cambia.
            await sendSubscriptionToServer(subscription);

        } catch (error) {
            console.error('Error en suscripción push:', error);
        }
    }

    // ---- Registrar Service Worker e intentar suscribir ----
    window.addEventListener('load', async () => {
        try {
            await navigator.serviceWorker.register('/sw.js', { scope: '/' });
            // Esperar a que el SW esté listo y entonces suscribir
            subscribeToPush();
        } catch (error) {
            console.error('Error registrando Service Worker:', error);
        }
    });
})();
</script>
@endauth
