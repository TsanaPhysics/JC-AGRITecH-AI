const V = 'leaf-ai-v1';
const FILES = ['./', 'index.html', 'app.js', 'style.css', 'manifest.json', 'icon.svg', 'labels.json', 'vendor/tf.min.js'];
self.addEventListener('install', e => e.waitUntil(caches.open(V).then(c => c.addAll(FILES)).then(() => self.skipWaiting())));
self.addEventListener('activate', e => e.waitUntil(caches.keys().then(k => Promise.all(k.filter(x => x !== V).map(x => caches.delete(x)))).then(() => self.clients.claim())));
self.addEventListener('fetch', e => e.respondWith(caches.match(e.request).then(r => r || fetch(e.request).then(n => { if (n.ok && new URL(e.request.url).origin === location.origin) { const c = n.clone(); caches.open(V).then(x => x.put(e.request, c)); } return n; }))));
