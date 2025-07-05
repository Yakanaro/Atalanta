FROM nginx:alpine

# Установка OpenSSL
RUN apk add --no-cache openssl

# Создание SSL сертификата
RUN mkdir -p /etc/ssl/private /etc/ssl/certs && \
    openssl genrsa -out /etc/ssl/private/ssl-cert-snakeoil.key 2048 && \
    openssl req -new -x509 -key /etc/ssl/private/ssl-cert-snakeoil.key \
        -out /etc/ssl/certs/ssl-cert-snakeoil.pem \
        -days 365 \
        -subj "/C=RU/ST=Moscow/L=Moscow/O=Atalanta/CN=localhost" && \
    chmod 600 /etc/ssl/private/ssl-cert-snakeoil.key && \
    chmod 644 /etc/ssl/certs/ssl-cert-snakeoil.pem

# Копирование конфигурации nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"] 