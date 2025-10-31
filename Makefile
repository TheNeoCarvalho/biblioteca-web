# Makefile para Sistema de Biblioteca
# Facilita o uso de comandos Docker

.PHONY: help install start stop restart logs shell test backup restore clean

# Variáveis
DOCKER_COMPOSE = docker-compose
APP_CONTAINER = biblioteca_app
DB_CONTAINER = biblioteca_db

# Comando padrão
help: ## Mostra esta ajuda
	@echo "Sistema de Biblioteca - Comandos Docker"
	@echo ""
	@echo "Uso: make [comando]"
	@echo ""
	@echo "Comandos disponíveis:"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  %-15s %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Instala e configura o projeto
	@echo "🚀 Instalando Sistema de Biblioteca..."
	@if [ ! -f .env ]; then cp .env.docker .env; fi
	@$(DOCKER_COMPOSE) build
	@$(DOCKER_COMPOSE) up -d
	@echo "⏳ Aguardando containers ficarem prontos..."
	@sleep 30
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan key:generate
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate --seed --force
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan storage:link
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan config:cache
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) chown -R www-data:www-data /var/www/storage
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) chmod -R 775 /var/www/storage
	@echo "✅ Instalação concluída!"
	@echo "🌐 Acesse: http://localhost:8080"

start: ## Inicia os containers
	@echo "🚀 Iniciando containers..."
	@$(DOCKER_COMPOSE) up -d

stop: ## Para os containers
	@echo "🛑 Parando containers..."
	@$(DOCKER_COMPOSE) down

restart: ## Reinicia os containers
	@echo "🔄 Reiniciando containers..."
	@$(DOCKER_COMPOSE) restart

logs: ## Mostra logs da aplicação
	@$(DOCKER_COMPOSE) logs -f $(APP_CONTAINER)

logs-all: ## Mostra logs de todos os containers
	@$(DOCKER_COMPOSE) logs -f

shell: ## Acessa o shell da aplicação
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) bash

shell-db: ## Acessa o shell do banco de dados
	@$(DOCKER_COMPOSE) exec $(DB_CONTAINER) mysql -u biblioteca_user -p sistema_biblioteca

test: ## Executa os testes
	@echo "🧪 Executando testes..."
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan test

migrate: ## Executa migrações
	@echo "🗄️ Executando migrações..."
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate

seed: ## Executa seeders
	@echo "🌱 Executando seeders..."
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan db:seed

fresh: ## Recria banco de dados com seeders
	@echo "🔄 Recriando banco de dados..."
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate:fresh --seed

cache-clear: ## Limpa todos os caches
	@echo "🧹 Limpando caches..."
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan cache:clear
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan config:clear
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan route:clear
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan view:clear

cache-optimize: ## Otimiza caches para produção
	@echo "⚡ Otimizando caches..."
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan config:cache
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan route:cache
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan view:cache

backup: ## Cria backup do banco de dados
	@echo "💾 Criando backup..."
	@mkdir -p backups
	@$(DOCKER_COMPOSE) exec $(DB_CONTAINER) mysqldump -u biblioteca_user -p biblioteca_pass_123 sistema_biblioteca > backups/backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "✅ Backup criado em backups/"

restore: ## Restaura backup (uso: make restore FILE=backup.sql)
	@if [ -z "$(FILE)" ]; then echo "❌ Uso: make restore FILE=backup.sql"; exit 1; fi
	@echo "🔄 Restaurando backup $(FILE)..."
	@$(DOCKER_COMPOSE) exec -T $(DB_CONTAINER) mysql -u biblioteca_user -p biblioteca_pass_123 sistema_biblioteca < backups/$(FILE)
	@echo "✅ Backup restaurado!"

clean: ## Remove containers, volumes e imagens
	@echo "🧹 Limpando containers, volumes e imagens..."
	@$(DOCKER_COMPOSE) down -v --rmi all
	@docker system prune -f

rebuild: ## Reconstrói containers do zero
	@echo "🔨 Reconstruindo containers..."
	@$(DOCKER_COMPOSE) down
	@$(DOCKER_COMPOSE) build --no-cache
	@$(DOCKER_COMPOSE) up -d

status: ## Mostra status dos containers
	@$(DOCKER_COMPOSE) ps

update: ## Atualiza dependências
	@echo "📦 Atualizando dependências..."
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) composer update
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate
	@make cache-optimize

prod-deploy: ## Deploy para produção
	@echo "🚀 Fazendo deploy para produção..."
	@$(DOCKER_COMPOSE) -f docker-compose.prod.yml up -d --build
	@$(DOCKER_COMPOSE) -f docker-compose.prod.yml exec app php artisan migrate --force
	@$(DOCKER_COMPOSE) -f docker-compose.prod.yml exec app php artisan config:cache
	@$(DOCKER_COMPOSE) -f docker-compose.prod.yml exec app php artisan route:cache
	@$(DOCKER_COMPOSE) -f docker-compose.prod.yml exec app php artisan view:cache

# Comandos de desenvolvimento
dev-install: ## Instala dependências de desenvolvimento
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) composer install
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) npm install

dev-watch: ## Inicia watcher para assets
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) npm run dev

# Comandos de monitoramento
monitor: ## Monitora recursos dos containers
	@docker stats

health: ## Verifica saúde dos containers
	@echo "🏥 Verificando saúde dos containers..."
	@$(DOCKER_COMPOSE) ps
	@echo ""
	@echo "📊 Uso de recursos:"
	@docker stats --no-stream