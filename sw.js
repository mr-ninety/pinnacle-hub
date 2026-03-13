// sw.js – Basic Service Worker for Unnnati PWA
// Caches static assets + allows offline access to landing page

const CACHE_NAME = 'unnnati-cache-v1';
const urlsToCache = [
  './',
  './index.php',
  './manifest.json',
  './robots.txt',
  'https://cdn.tailwindcss.com',
  'https://unpkg.com/aos@2.3.1/dist/aos.css',
  'https://unpkg.com/aos@2.3.1/dist/aos.js',
  'https://cdn.jsdelivr.net/npm/chart.js',
  // Add your own static assets here later, e.g.:
  // './style.css',
  // './uploads/icon-192.png',
  // './uploads/icon-512.png',
  // './resume.pdf'   ← optional, if small
];

// Install event – cache core assets
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
      // Skip waiting so new SW activates immediately
      .then(() => self.skipWaiting())
  );
});

// Activate event – clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.filter(name => name !== CACHE_NAME)
          .map(name => caches.delete(name))
      );
    })
    // Take control of the page immediately
    .then(() => self.clients.claim())
  );
});

// Fetch event – Cache-first for cached resources, network-first for everything else
self.addEventListener('fetch', event => {
  // Skip non-GET requests and browser dev tools
  if (event.request.method !== 'GET' || event.request.url.includes('browser-sync')) {
    event.respondWith(fetch(event.request));
    return;
  }

  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Cache hit – return cached version
        if (response) {
          return response;
        }

        // Clone the request because it's a stream that can only be consumed once
        const fetchRequest = event.request.clone();

        return fetch(fetchRequest).then(networkResponse => {
          // Check if valid response
          if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
            return networkResponse;
          }

          // Clone again for caching
          const responseToCache = networkResponse.clone();

          caches.open(CACHE_NAME)
            .then(cache => {
              cache.put(event.request, responseToCache);
            });

          return networkResponse;
        })
        .catch(() => {
          // Optional: fallback page / offline.html when both cache & network fail
          // For now we just let it fail naturally (most browsers show default offline page)
        });
      })
  );
});