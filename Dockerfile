FROM php:8.2-fpm

# Настройка apt для игнорирования GPG проверок
RUN echo 'APT::Get::AllowUnauthenticated "true";' > /etc/apt/apt.conf.d/99ignore-gpg \
    && echo 'Acquire::AllowInsecureRepositories "true";' >> /etc/apt/apt.conf.d/99ignore-gpg \
    && echo 'Acquire::AllowDowngradeToInsecureRepositories "true";' >> /etc/apt/apt.conf.d/99ignore-gpg

# Очистка кэша и установка базовых пакетов
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*

# Установка основных пакетов
RUN apt-get update --allow-releaseinfo-change && apt-get install -y \
    git \
    curl \
    ca-certificates \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*

# Установка development библиотек
RUN apt-get update --allow-releaseinfo-change && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libmagickwand-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*

# Установка утилит
RUN apt-get update --allow-releaseinfo-change && apt-get install -y \
    zip \
    unzip \
    supervisor \
    cron \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*

# Установка Node.js из официального репозитория NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /var/cache/apt/archives/*

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

# Настройка PHP для больших файлов
RUN echo "upload_max_filesize = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 600" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_input_time = 600" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/uploads.ini

# Установка ImageMagick расширения
RUN pecl install imagick && docker-php-ext-enable imagick

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