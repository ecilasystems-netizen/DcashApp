const CACHE_VERSION = 'v5'; // bump this on every deploy
const CACHE_NAME = `dcash-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline';

self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache =>
            cache.addAll([
                OFFLINE_URL,
                '/manifest.json'
            ])
        )
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        Promise.all([
            self.clients.claim(),
            caches.keys().then(cacheNames =>
                Promise.all(
                    cacheNames.map(cache => {
                        if (cache !== CACHE_NAME) {
                            return caches.delete(cache);
                        }
                    })
                )
            )
        ])
    );
});

self.addEventListener('fetch', event => {
    const { request } = event;

    // 🟢 HTML: Network First (always get latest UI)
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then(response => {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    return response;
                })
                .catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    // 🟡 JS / CSS: Stale-While-Revalidate
    if (
        request.destination === 'script' ||
        request.destination === 'style'
    ) {
        event.respondWith(
            caches.match(request).then(cached => {
                const networkFetch = fetch(request).then(response => {
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(request, response.clone());
                    });
                    return response;
                });
                return cached || networkFetch;
            })
        );
        return;
    }

    // 🔵 Everything else
    event.respondWith(
        fetch(request).catch(() => caches.match(request))
    );
});
