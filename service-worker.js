const CACHE_NAME = 'mybook-app-cache-v3';
const urlsToCache = [
  '/',
  '/css/custom.css',
  '/js/mybook.js',
  '/img/favicon/android-chrome-192x192.png',
  '/img/favicon/android-chrome-512x512.png'
];

// Install the service worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

// Fetch cached assets
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});