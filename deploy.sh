#!/bin/bash

# Цвета для вывода
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Деплой Laravel приложения Atalanta${NC}"
echo "========================================"

# Проверка существования .env файла
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  Файл .env не найден. Создаю из шаблона...${NC}"
    
    if [ -f docker/production.env ]; then
        cp docker/production.env .env
        echo -e "${GREEN}✅ .env файл создан из шаблона${NC}"
        echo -e "${YELLOW}⚠️  ВНИМАНИЕ: Отредактируйте .env файл перед продолжением!${NC}"
        echo -e "${YELLOW}   Особенно важно изменить:${NC}"
        echo -e "${YELLOW}   - APP_KEY (сгенерируйте новый)${NC}"
        echo -e "${YELLOW}   - DB_PASSWORD (безопасный пароль)${NC}"
        echo -e "${YELLOW}   - APP_URL (ваш домен)${NC}"
        echo -e "${YELLOW}   - Настройки почты${NC}"
        echo ""
        read -p "Нажмите Enter после редактирования .env файла..."
    else
        echo -e "${RED}❌ Шаблон .env файла не найден!${NC}"
        exit 1
    fi
fi

# Проверка Docker
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker не установлен!${NC}"
    exit 1
fi

if ! docker compose version &> /dev/null; then
    echo -e "${RED}❌ Docker Compose не установлен!${NC}"
    exit 1
fi

echo -e "${GREEN}🔨 Сборка Docker контейнеров...${NC}"
docker compose build

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Ошибка сборки контейнеров!${NC}"
    exit 1
fi

echo -e "${GREEN}🚀 Запуск контейнеров...${NC}"
docker compose up -d

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Ошибка запуска контейнеров!${NC}"
    exit 1
fi

echo -e "${GREEN}⏳ Ожидание запуска контейнеров...${NC}"
sleep 10

echo -e "${GREEN}🗄️  Выполнение миграций...${NC}"
docker compose exec app php artisan migrate --force

echo -e "${GREEN}🔗 Создание символической ссылки для storage...${NC}"
docker compose exec app php artisan storage:link

echo -e "${GREEN}⚡ Сборка кеша...${NC}"
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

echo -e "${GREEN}📊 Статус контейнеров:${NC}"
docker compose ps

echo ""
echo -e "${GREEN}✅ Деплой завершен успешно!${NC}"
echo -e "${GREEN}🌐 Приложение доступно по адресу: http://localhost${NC}"
echo ""
echo -e "${YELLOW}Полезные команды:${NC}"
echo -e "${YELLOW}  docker compose ps${NC}           - статус контейнеров"
echo -e "${YELLOW}  docker compose logs -f${NC}      - логи"
echo -e "${YELLOW}  docker compose exec app bash${NC} - вход в контейнер"
echo -e "${YELLOW}  make help${NC}                    - все доступные команды"
echo "" 