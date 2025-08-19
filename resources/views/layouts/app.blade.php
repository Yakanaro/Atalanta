<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full bg-gray-900">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">



    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- 1) Vendor CSS first -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />

    <!-- 2) Our CSS last (should win the cascade on prod) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Force page background in case external CSS overrides it (prod mobile fix) -->
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#111827">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f3f4f6">
    <style>
        :root { color-scheme: dark light; }
        html { background-color: #111827; }
        @media (prefers-color-scheme: light) { html { background-color: #f3f4f6; } }
        html.dark { background-color: #111827 !important; }
        html.dark body { background-color: #111827 !important; }
        body { min-height: 100svh; }
        /* Ensure white utility does not show up in dark theme on prod (Flowbite CDN order) */
        html.dark .bg-white { background-color: #1f2937 !important; }
    </style>

    <!-- Flowbite CSS removed: styles come from Tailwind build via plugin to avoid overriding dark classes -->

    <!-- Установка темной темы по умолчанию -->
    <script>
        // Устанавливаем темную тему по умолчанию
        document.documentElement.classList.add('dark');
        localStorage.setItem('color-theme', 'dark');
    </script>

    <!-- Additional Styles -->
    @stack('styles')
</head>

<body class="font-sans antialiased min-h-screen min-h-[100svh] bg-gray-100 dark:bg-gray-900">
        <div class="min-h-full">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
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

            // Элементы для мобильного переключателя (авторизованные пользователи)
            var themeToggleDarkIconMobile = document.getElementById('theme-toggle-dark-icon-mobile');
            var themeToggleLightIconMobile = document.getElementById('theme-toggle-light-icon-mobile');
            var themeToggleBtnMobile = document.getElementById('theme-toggle-mobile');

            // Элементы для мобильного переключателя (неавторизованные пользователи)
            var themeToggleDarkIconMobileGuest = document.getElementById('theme-toggle-dark-icon-mobile-guest');
            var themeToggleLightIconMobileGuest = document.getElementById('theme-toggle-light-icon-mobile-guest');
            var themeToggleBtnMobileGuest = document.getElementById('theme-toggle-mobile-guest');

            // Функция для установки правильных иконок
            function setCorrectIcons() {
                if (localStorage.getItem('color-theme') === 'dark' ||
                    (!('color-theme' in localStorage) && document.documentElement.classList.contains('dark'))) {
                    // Показываем иконку солнца (светлая тема)
                    if (themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
                    if (themeToggleDarkIcon) themeToggleDarkIcon.classList.add('hidden');
                    if (themeToggleLightIconMobile) themeToggleLightIconMobile.classList.remove('hidden');
                    if (themeToggleDarkIconMobile) themeToggleDarkIconMobile.classList.add('hidden');
                    if (themeToggleLightIconMobileGuest) themeToggleLightIconMobileGuest.classList.remove('hidden');
                    if (themeToggleDarkIconMobileGuest) themeToggleDarkIconMobileGuest.classList.add('hidden');
                } else {
                    // Показываем иконку луны (темная тема)
                    if (themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
                    if (themeToggleLightIcon) themeToggleLightIcon.classList.add('hidden');
                    if (themeToggleDarkIconMobile) themeToggleDarkIconMobile.classList.remove('hidden');
                    if (themeToggleLightIconMobile) themeToggleLightIconMobile.classList.add('hidden');
                    if (themeToggleDarkIconMobileGuest) themeToggleDarkIconMobileGuest.classList.remove('hidden');
                    if (themeToggleLightIconMobileGuest) themeToggleLightIconMobileGuest.classList.add('hidden');
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

            if (themeToggleBtnMobileGuest) {
                themeToggleBtnMobileGuest.addEventListener('click', toggleTheme);
            }
        });
    </script>



    <!-- Additional Scripts -->
    @stack('scripts')
</body>

</html>