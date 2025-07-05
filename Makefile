# Makefile для управления Docker контейнерами

.PHONY: help build up down restart logs shell migrate seed fresh install deploy

# Цвета для вывода
RED=\033[0;31m
GREEN=\033[0;32m
YELLOW=\033[0;33m
NC=\033[0m # No Color

help: ## Показать справку
	@echo "$(GREEN)Доступные команды:$(NC)"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "$(YELLOW)%-15s$(NC) %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Сборка Docker контейнеров
	@echo "$(GREEN)Сборка Docker контейнеров...$(NC)"
	docker compose build

up: ## Запуск контейнеров
	@echo "$(GREEN)Запуск контейнеров...$(NC)"
	docker compose up -d

down: ## Остановка контейнеров
	@echo "$(YELLOW)Остановка контейнеров...$(NC)"
	docker compose down

restart: ## Перезапуск контейнеров
	@echo "$(YELLOW)Перезапуск контейнеров...$(NC)"
	docker compose restart

logs: ## Просмотр логов
	@echo "$(GREEN)Просмотр логов...$(NC)"
	docker compose logs -f

logs-app: ## Просмотр логов приложения
	@echo "$(GREEN)Просмотр логов приложения...$(NC)"
	docker compose logs -f app

shell: ## Вход в контейнер приложения
	@echo "$(GREEN)Вход в контейнер приложения...$(NC)"
	docker compose exec app bash

migrate: ## Выполнение миграций
	@echo "$(GREEN)Выполнение миграций...$(NC)"
	docker compose exec app php artisan migrate

migrate-fresh: ## Пересоздание базы данных и миграций
	@echo "$(RED)Пересоздание базы данных...$(NC)"
	docker compose exec app php artisan migrate:fresh

seed: ## Выполнение сидеров
	@echo "$(GREEN)Выполнение сидеров...$(NC)"
	docker compose exec app php artisan db:seed

fresh: migrate-fresh seed ## Пересоздание БД и заполнение тестовыми данными

install: ## Установка зависимостей
	@echo "$(GREEN)Установка зависимостей...$(NC)"
	docker compose exec app composer install
	docker compose exec app npm install

cache-clear: ## Очистка кеша
	@echo "$(GREEN)Очистка кеша...$(NC)"
	docker compose exec app php artisan cache:clear
	docker compose exec app php artisan config:clear
	docker compose exec app php artisan route:clear
	docker compose exec app php artisan view:clear

cache-build: ## Сборка кеша
	@echo "$(GREEN)Сборка кеша...$(NC)"
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache

deploy: build up migrate cache-build ## Полный деплой
	@echo "$(GREEN)Деплой завершен!$(NC)"

status: ## Статус контейнеров
	@echo "$(GREEN)Статус контейнеров:$(NC)"
	docker compose ps

backup: ## Создание резервной копии БД
	@echo "$(GREEN)Создание резервной копии...$(NC)"
	docker compose exec mysql mysqldump -u atalanta -p atalanta > backup_$(shell date +%Y%m%d_%H%M%S).sql

clean: ## Очистка Docker системы
	@echo "$(YELLOW)Очистка Docker системы...$(NC)"
	docker system prune -f
	docker volume prune -f

dev: ## Запуск в режиме разработки
	@echo "$(GREEN)Запуск в режиме разработки...$(NC)"
	docker compose -f docker-compose.yml -f docker-compose.override.yml up -d

prod: ## Запуск в production режиме
	@echo "$(GREEN)Запуск в production режиме...$(NC)"
	docker compose -f docker-compose.yml up -d 