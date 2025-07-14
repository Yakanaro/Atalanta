<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1f2937">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Atalanta">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Atalanta">
    <meta name="msapplication-TileColor" content="#1f2937">
    <meta name="msapplication-tap-highlight" content="no">

    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- PWA Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icon-512x512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('icon-512x512.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flowbite CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />

    <!-- Установка темной темы по умолчанию -->
    <script>
        // Устанавливаем темную тему по умолчанию
        document.documentElement.classList.add('dark');
        localStorage.setItem('color-theme', 'dark');
    </script>

    <!-- Additional Styles -->
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <!-- Pull to Refresh Container -->
    <div class="pull-to-refresh-container">
        <!-- Pull to Refresh Icon -->
        <div id="pull-to-refresh-icon" class="pull-to-refresh-icon text-gray-600 dark:text-gray-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </div>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="pwa-navigation">
                @include('layouts.navigation')
            </div>

            <!-- Page Heading -->
            @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow pwa-main-content">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endisset

            <!-- Page Content -->
            <main class="pwa-main-content">
                {{ $slot }}
            </main>

            <!-- Bottom Safe Area -->
            <div class="pwa-bottom-safe"></div>
        </div>
    </div>

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>

    <!-- JavaScript для переключателя темы -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Элементы для десктопного переключателя
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
            var themeToggleBtn = document.getElementById('theme-toggle');

            // Элементы для мобильного переключателя
            var themeToggleDarkIconMobile = document.getElementById('theme-toggle-dark-icon-mobile');
            var themeToggleLightIconMobile = document.getElementById('theme-toggle-light-icon-mobile');
            var themeToggleBtnMobile = document.getElementById('theme-toggle-mobile');

            // Функция для установки правильных иконок
            function setCorrectIcons() {
                if (localStorage.getItem('color-theme') === 'dark' ||
                    (!('color-theme' in localStorage) && document.documentElement.classList.contains('dark'))) {
                    // Показываем иконку солнца (светлая тема)
                    themeToggleLightIcon.classList.remove('hidden');
                    themeToggleDarkIcon.classList.add('hidden');
                    themeToggleLightIconMobile.classList.remove('hidden');
                    themeToggleDarkIconMobile.classList.add('hidden');
                } else {
                    // Показываем иконку луны (темная тема)
                    themeToggleDarkIcon.classList.remove('hidden');
                    themeToggleLightIcon.classList.add('hidden');
                    themeToggleDarkIconMobile.classList.remove('hidden');
                    themeToggleLightIconMobile.classList.add('hidden');
                }
            }

            // Устанавливаем правильные иконки при загрузке
            setCorrectIcons();

            // Функция для переключения темы
            function toggleTheme() {
                // Если текущая тема темная
                if (localStorage.getItem('color-theme') === 'dark' ||
                    (!('color-theme' in localStorage) && document.documentElement.classList.contains('dark'))) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }

                // Обновляем иконки
                setCorrectIcons();
            }

            // Обработчики событий для кнопок
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', toggleTheme);
            }

            if (themeToggleBtnMobile) {
                themeToggleBtnMobile.addEventListener('click', toggleTheme);
            }
        });
    </script>

    <!-- PWA Scripts -->
    <script>
        // Регистрация Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(function(error) {
                        console.log('ServiceWorker registration failed: ', error);
                    });
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        const installButton = document.getElementById('pwa-install-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (installButton) {
                installButton.style.display = 'block';
            }
        });

        if (installButton) {
            installButton.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const {
                        outcome
                    } = await deferredPrompt.userChoice;
                    console.log(`User response to the install prompt: ${outcome}`);
                    deferredPrompt = null;
                    installButton.style.display = 'none';
                }
            });
        }

        // Скрыть кнопку если приложение уже установлено
        window.addEventListener('appinstalled', (evt) => {
            console.log('PWA was installed');
            if (installButton) {
                installButton.style.display = 'none';
            }
        });

        // Pull to Refresh Functionality
        let startY = 0;
        let currentY = 0;
        let isRefreshing = false;
        let pullStarted = false;

        const pullToRefreshIcon = document.getElementById('pull-to-refresh-icon');
        const pullContainer = document.querySelector('.pull-to-refresh-container');

        function handleTouchStart(e) {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                pullStarted = true;
            }
        }

        function handleTouchMove(e) {
            if (!pullStarted || isRefreshing || window.scrollY > 0) return;

            currentY = e.touches[0].clientY;
            const diffY = currentY - startY;

            if (diffY > 0) {
                e.preventDefault();

                if (diffY > 60) {
                    pullToRefreshIcon.classList.add('visible');
                } else {
                    pullToRefreshIcon.classList.remove('visible');
                }
            }
        }

        function handleTouchEnd(e) {
            if (!pullStarted || isRefreshing) return;

            pullStarted = false;
            const diffY = currentY - startY;

            if (diffY > 60) {
                isRefreshing = true;
                pullToRefreshIcon.classList.add('loading');

                // Обновляем страницу
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                pullToRefreshIcon.classList.remove('visible');
            }
        }

        // Добавляем обработчики событий
        document.addEventListener('touchstart', handleTouchStart, {
            passive: false
        });
        document.addEventListener('touchmove', handleTouchMove, {
            passive: false
        });
        document.addEventListener('touchend', handleTouchEnd, {
            passive: false
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>

</html>