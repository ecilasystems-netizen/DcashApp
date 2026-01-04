const CACHE_NAME = 'dcash-v2';

self.addEventListener('install', event => {
    // Add skipWaiting() to activate the new service worker immediately
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll([
                '/', // commented to avoid hitting the dashboard component on every page
                '/offline',
                '/css/app.css',
                '/js/app.js',
                '/manifest.json'
            ]);
        })
    );
});

self.addEventListener('activate', event => {
    // Add clients.claim() to take control of open pages
    event.waitUntil(
        clients.claim().then(() => {
            return caches.keys().then(cacheNames => {
                return Promise.all(
                    cacheNames.filter(name => name !== CACHE_NAME)
                        .map(name => caches.delete(name))
                );
            });
        })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => response || fetch(event.request))
            .catch(() => caches.match('/offline'))
    );
});
