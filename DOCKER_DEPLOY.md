# Деплой Docker контейнеров на reg.ru

## Подготовка к деплою

### 1. Создание .env файла для production

Создайте файл `.env` в корне проекта со следующими настройками:

```env
APP_NAME=Atalanta
APP_ENV=production
APP_KEY=base64:ВАША_ГЕНЕРИРОВАННАЯ_КЛЮЧ_ПРИЛОЖЕНИЯ
APP_DEBUG=false
APP_URL=https://ВАШ_ДОМЕН.ru

# База данных
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=atalanta
DB_USERNAME=atalanta
DB_PASSWORD=ВАША_БЕЗОПАСНАЯ_ПАРОЛЬНАЯ_ФРАЗА

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Кеширование и сессии
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Почта (настройте согласно вашим параметрам)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.ru
MAIL_PORT=465
MAIL_USERNAME=ваш_email@mail.ru
MAIL_PASSWORD=ваш_пароль
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=ваш_email@mail.ru
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Генерация APP_KEY

Выполните локально:
```bash
php artisan key:generate --show
```

Скопируйте сгенерированный ключ в файл `.env`.

## Деплой на reg.ru

### 1. Загрузка проекта на сервер

Загрузите все файлы проекта на ваш сервер reg.ru через FTP или Git.

### 2. Сборка и запуск контейнеров

```bash
# Сборка контейнеров
docker-compose build

# Запуск в production режиме
docker-compose up -d
```

### 3. Проверка статуса

```bash
# Проверка работающих контейнеров
docker-compose ps

# Просмотр логов
docker-compose logs -f

# Просмотр логов конкретного сервиса
docker-compose logs -f app
```

### 4. Выполнение команд Laravel

```bash
# Вход в контейнер приложения
docker-compose exec app bash

# Выполнение миграций
docker-compose exec app php artisan migrate

# Создание пользователя
docker-compose exec app php artisan tinker

# Очистка кеша
docker-compose exec app php artisan cache:clear
```

## Настройка домена

### 1. Настройка DNS

Направьте ваш домен на IP-адрес сервера reg.ru.

### 2. Настройка SSL (рекомендуется)

Для SSL сертификата создайте дополнительный файл `docker-compose.ssl.yml`:

```yaml
version: '3.8'

services:
  nginx:
    volumes:
      - ./ssl:/etc/nginx/ssl:ro
      - ./docker/nginx/nginx-ssl.conf:/etc/nginx/conf.d/default.conf
    ports:
      - "443:443"
```

### 3. Обновление nginx конфигурации для SSL

Создайте файл `docker/nginx/nginx-ssl.conf` с поддержкой HTTPS.

## Резервное копирование

### Настройка автоматического бекапа базы данных

```bash
# Создание бекапа
docker-compose exec mysql mysqldump -u atalanta -p atalanta > backup_$(date +%Y%m%d_%H%M%S).sql

# Восстановление из бекапа
docker-compose exec -T mysql mysql -u atalanta -p atalanta < backup_file.sql
```

## Мониторинг и логи

### Настройка логирования

```bash
# Просмотр логов приложения
docker-compose logs -f app

# Просмотр логов nginx
docker-compose logs -f nginx

# Просмотр логов базы данных
docker-compose logs -f mysql

# Просмотр логов очередей
docker-compose logs -f queue
```

### Мониторинг ресурсов

```bash
# Статистика использования ресурсов
docker stats

# Использование дискового пространства
docker system df
```

## Обновление приложения

```bash
# Остановка контейнеров
docker-compose down

# Получение обновлений
git pull origin main

# Пересборка контейнеров
docker-compose build --no-cache

# Запуск обновленных контейнеров
docker-compose up -d

# Выполнение новых миграций
docker-compose exec app php artisan migrate --force
```

## Устранение неполадок

### Проблемы с правами доступа

```bash
# Исправление прав доступа к storage
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage
```

### Проблемы с кешем

```bash
# Очистка всех кешей
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Перезапуск сервисов

```bash
# Перезапуск всех сервисов
docker-compose restart

# Перезапуск конкретного сервиса
docker-compose restart app
```

## Важные замечания

1. **Безопасность**: Используйте сильные пароли для базы данных
2. **Бекапы**: Регулярно создавайте резервные копии
3. **Обновления**: Следите за обновлениями безопасности
4. **Мониторинг**: Настройте мониторинг производительности
5. **Логи**: Регулярно проверяйте логи на наличие ошибок

## Поддержка

Если у вас возникли проблемы с деплоем, проверьте:
- Логи контейнеров
- Настройки DNS
- Файл `.env`
- Права доступа к файлам 