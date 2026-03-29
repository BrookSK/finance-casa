const CACHE_NAME = 'financas-casal-v2';
const STATIC_ASSETS = [
    '/assets/css/app.css',
    '/assets/js/app.js',
    '/manifest.json',
];

// Install — cachear assets estáticos
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(STATIC_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// Activate — limpar caches antigos
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(
                keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k))
            ))
            .then(() => self.clients.claim())
    );
});

// Fetch — network first para páginas, cache first para assets
self.addEventListener('fetch', (event) => {
    const { request } = event;

    // Ignorar POST e requests não-GET
    if (request.method !== 'GET') return;

    // Assets estáticos: cache first
    if (request.url.includes('/assets/') || request.url.includes('/manifest.json')) {
        event.respondWith(
            caches.match(request).then(cached => {
                const fetchPromise = fetch(request).then(response => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    }
                    return response;
                }).catch(() => cached);

                return cached || fetchPromise;
            })
        );
        return;
    }

    // Páginas HTML: network first, fallback cache
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    }
                    return response;
                })
                .catch(() => caches.match(request).then(cached => {
                    return cached || caches.match('/dashboard');
                }))
        );
        return;
    }
});

// Push notifications
self.addEventListener('push', (event) => {
    let data = { title: 'FinançasCasal', body: 'Você tem uma nova notificação' };

    if (event.data) {
        try {
            data = event.data.json();
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body || data.mensagem || '',
        icon: '/assets/img/icon-192.png',
        badge: '/assets/img/icon-192.png',
        vibrate: [200, 100, 200],
        tag: data.tag || 'default',
        data: { url: data.url || data.link || '/dashboard' },
        actions: [
            { action: 'open', title: 'Abrir' },
            { action: 'close', title: 'Fechar' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title || data.titulo || 'FinançasCasal', options)
    );
});

// Clique na notificação
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'close') return;

    const url = event.notification.data?.url || '/dashboard';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then(windowClients => {
                // Se já tem uma aba aberta, foca nela
                for (const client of windowClients) {
                    if (client.url.includes(self.location.origin)) {
                        client.navigate(url);
                        return client.focus();
                    }
                }
                // Senão abre nova aba
                return clients.openWindow(url);
            })
    );
});
