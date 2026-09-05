self.addEventListener('install', function (evento) {
    self.skipWaiting();
});

self.addEventListener('activate', function (evento) {
    self.clients.claim();
});