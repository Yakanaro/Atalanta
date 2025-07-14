const CACHE_NAME = 'atalanta-v1';
const urlsToCache = [
  '/',
  '/dashboard',
  '/settings',
  '/pallet/create',
  '/css/app.css',
  '/js/app.js',
  '/favicon.ico',
  '/manifest.json'
];

// Установка service worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Обработка fetch запросов
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Возвращаем кэшированный ответ если он есть
        if (response) {
          return response;
        }
        
        // Клонируем запрос
        const fetchRequest = event.request.clone();
        
        return fetch(fetchRequest).then(response => {
          // Проверяем что ответ валидный
          if (!response || response.status !== 200 || response.type !== 'basic') {
            return response;
          }
          
          // Клонируем ответ
          const responseToCache = response.clone();
          
          caches.open(CACHE_NAME)
            .then(cache => {
              cache.put(event.request, responseToCache);
            });
          
          return response;
        }).catch(() => {
          // Если сеть недоступна, возвращаем кэшированную главную страницу
          if (event.request.destination === 'document') {
            return caches.match('/');
          }
        });
      })
  );
});

// Активация service worker
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Обработка фоновой синхронизации
self.addEventListener('sync', event => {
  if (event.tag === 'background-sync') {
    event.waitUntil(doBackgroundSync());
  }
});

function doBackgroundSync() {
  // Здесь можно добавить логику для синхронизации данных в фоне
  console.log('Background sync triggered');
} 