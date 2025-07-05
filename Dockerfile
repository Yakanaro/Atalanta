FROM php:8.2-fpm

# Обновление GPG ключей и установка системных зависимостей
RUN apt-get update && apt-get install -y \
    gnupg2 \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Обновление ключей и установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    supervisor \
    cron \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Установка Node.js из официального репозитория NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Установка PHP расширений
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    xml

# Установка Redis расширения
RUN pecl install redis && docker-php-ext-enable redis

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Создание рабочей директории
WORKDIR /var/www

# Копирование composer файлов
COPY composer.json ./
COPY composer.lock* ./

# Установка PHP зависимостей
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Копирование package.json и package-lock.json
COPY package*.json ./

# Установка всех Node.js зависимостей (включая dev)
RUN npm ci

# Копирование остальных файлов проекта
COPY . .

# Сборка фронтенда
RUN npm run build

# Удаление dev зависимостей
RUN npm prune --production

# Установка правильных прав доступа
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Создание символической ссылки для публичного хранилища
RUN php artisan storage:link || true

# Копирование entrypoint скрипта
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Экспорт портов
EXPOSE 9000

# Entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]

# Команда по умолчанию
CMD ["php-fpm"] 