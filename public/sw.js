const CACHE_NAME = 'atalanta-v2';
const urlsToCache = [
  '/',
  '/dashboard',
  '/settings',
  '/pallet/create',
  '/stock-position/create',
  '/css/app.css',
  '/js/app.js',
  '/favicon.ico',
  '/favicon.svg',
  '/icon-192x192.png',
  '/icon-512x512.png',
  '/manifest.json',
  'https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap',
  'https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css',
  'https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js'
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
  // Форсируем активацию нового SW
  self.skipWaiting();
});

// Обработка fetch запросов
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Возвращаем кэшированный ответ если он есть
        if (response) {
          // Для HTML страниц всегда проверяем обновления в фоне
          if (event.request.headers.get('accept').includes('text/html')) {
            fetchAndCache(event.request);
          }
          return response;
        }
        
        // Если нет в кэше, загружаем из сети
        return fetchAndCache(event.request);
      })
      .catch(() => {
        // Если сеть недоступна, возвращаем кэшированную главную страницу
        if (event.request.destination === 'document') {
          return caches.match('/');
        }
      })
  );
});

// Функция для загрузки и кеширования
function fetchAndCache(request) {
  return fetch(request)
    .then(response => {
      // Проверяем что ответ валидный
      if (!response || response.status !== 200 || response.type !== 'basic') {
        return response;
      }
      
      // Клонируем ответ для кэширования
      const responseToCache = response.clone();
      
      caches.open(CACHE_NAME)
        .then(cache => {
          cache.put(request, responseToCache);
        });
      
      return response;
    });
}

// Активация service worker
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log('Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  // Берем контроль над всеми клиентами
  self.clients.claim();
});

// Обработка push уведомлений
self.addEventListener('push', event => {
  if (event.data) {
    const data = event.data.json();
    const options = {
      body: data.body,
      icon: '/icon-192x192.png',
      badge: '/icon-192x192.png',
      tag: 'atalanta-notification',
      requireInteraction: true,
      actions: [
        {
          action: 'open',
          title: 'Открыть',
          icon: '/icon-192x192.png'
        },
        {
          action: 'close',
          title: 'Закрыть'
        }
      ]
    };
    
    event.waitUntil(
      self.registration.showNotification(data.title, options)
    );
  }
});

// Обработка кликов по уведомлениям
self.addEventListener('notificationclick', event => {
  event.notification.close();
  
  if (event.action === 'open') {
    event.waitUntil(
      clients.openWindow('/')
    );
  }
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
  
  // Обновляем кэш важных страниц
  return Promise.all([
    caches.open(CACHE_NAME).then(cache => {
      return cache.add('/dashboard');
    }),
    caches.open(CACHE_NAME).then(cache => {
      return cache.add('/settings');
    })
  ]);
}

// Обработка обновлений кэша
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'CACHE_URLS') {
    event.waitUntil(
      caches.open(CACHE_NAME).then(cache => {
        return cache.addAll(event.data.payload);
      })
    );
  }
});

// Периодическая синхронизация (если поддерживается)
self.addEventListener('periodicsync', event => {
  if (event.tag === 'periodic-background-sync') {
    event.waitUntil(doBackgroundSync());
  }
}); 