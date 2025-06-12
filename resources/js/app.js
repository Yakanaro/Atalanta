import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Скрипт для управления темой
document.addEventListener('DOMContentLoaded', function() {
    // Проверяем, есть ли сохраненная тема в localStorage
    const theme = localStorage.getItem('color-theme');
    
    // Если тема не установлена, устанавливаем темную тему по умолчанию
    if (!theme) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('color-theme', 'dark');
    } else if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
});
