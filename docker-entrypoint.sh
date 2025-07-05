#!/bin/bash
set -e

# Проверка переменных окружения
if [ -z "$APP_KEY" ]; then
    echo "Генерация APP_KEY..."
    php artisan key:generate --ansi
fi

# Кэширование конфигурации
echo "Кэширование конфигурации..."
php artisan config:cache

# Кэширование маршрутов
echo "Кэширование маршрутов..."
php artisan route:cache

# Кэширование представлений
echo "Кэширование представлений..."
php artisan view:cache

# Выполнение миграций
echo "Выполнение миграций..."
php artisan migrate --force

# Создание символической ссылки для storage
echo "Создание символической ссылки для storage..."
php artisan storage:link

# Установка правильных прав доступа
echo "Установка прав доступа..."
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache

echo "Инициализация завершена!"

# Выполнение переданной команды
exec "$@" 