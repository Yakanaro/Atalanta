#!/bin/sh

# Создание самоподписанного SSL сертификата
if [ ! -f /etc/ssl/certs/ssl-cert-snakeoil.pem ]; then
    echo "Создание самоподписанного SSL сертификата..."
    
    # Установка OpenSSL если его нет
    apk add --no-cache openssl
    
    # Создание приватного ключа
    openssl genrsa -out /etc/ssl/private/ssl-cert-snakeoil.key 2048
    
    # Создание самоподписанного сертификата
    openssl req -new -x509 -key /etc/ssl/private/ssl-cert-snakeoil.key \
        -out /etc/ssl/certs/ssl-cert-snakeoil.pem \
        -days 365 \
        -subj "/C=RU/ST=Moscow/L=Moscow/O=Atalanta/CN=localhost"
    
    # Установка правильных прав
    chmod 600 /etc/ssl/private/ssl-cert-snakeoil.key
    chmod 644 /etc/ssl/certs/ssl-cert-snakeoil.pem
    
    echo "SSL сертификат создан успешно"
else
    echo "SSL сертификат уже существует"
fi

# Запуск nginx
exec nginx -g 'daemon off;' 