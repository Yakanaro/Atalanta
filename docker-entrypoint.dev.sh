#!/bin/bash
set -e

if [ -z "$APP_KEY" ] && [ ! -f .env ] || [ -f .env ] && ! grep -q "APP_KEY=.*[^=]" .env; then
    echo "Генерация APP_KEY..."
    php artisan key:generate --ansi
fi

echo "Пересоздание автозагрузки composer..."
composer dump-autoload --no-interaction --quiet || echo "Предупреждение: не удалось пересоздать автозагрузку"

echo "Очистка кэша конфигурации..."
php artisan config:clear || true
rm -f bootstrap/cache/config.php bootstrap/cache/routes*.php bootstrap/cache/services.php || true

echo "Очистка кэша маршрутов..."
php artisan route:clear || true

echo "Очистка кэша представлений..."
php artisan view:clear || true

echo "Очистка кэша подключений к БД..."
php artisan tinker --execute="DB::purge('mysql');" || true

echo "Выполнение миграций..."
php artisan migrate --force || echo "Предупреждение: миграции не выполнены"

echo "Создание символической ссылки для storage..."
php artisan storage:link || true

echo "Установка прав доступа..."
chown -R www-data:www-data /var/www/storage || true
chown -R www-data:www-data /var/www/bootstrap/cache || true
chmod -R 775 /var/www/storage || true
chmod -R 775 /var/www/bootstrap/cache || true

echo "Инициализация завершена!"

exec "$@"
