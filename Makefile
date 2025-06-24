# Define o shell padrão
SHELL := /bin/bash

# Comandos Docker Compose
COMPOSE_UP = docker-compose up --build -d
COMPOSE_DOWN = docker-compose down
COMPOSE_EXEC_APP = docker-compose exec app

# Comandos Artisan e Composer
ARTISAN = $(COMPOSE_EXEC_APP) php artisan
COMPOSER = $(COMPOSE_EXEC_APP) composer

# Silenciar saídas padrão
.SILENT:

# Alvos que não são arquivos
.PHONY: setup up down stop logs artisan composer npm adminlte ui

## --------------------------------------
## Setup Inicial
## --------------------------------------

setup: up ## Executa toda a configuração inicial do projeto
	@echo "📦 Instalando dependências do Composer..."
	$(COMPOSER) install
	@echo "📁 Copiando .env.example para .env (se necessário)..."
	cp -n .env.example .env || true
	@echo "🔑 Gerando chave da aplicação..."
	$(ARTISAN) key:generate
	@echo "🔧 Limpando e cacheando configs..."
	$(ARTISAN) config:clear
	$(ARTISAN) cache:clear
	$(ARTISAN) config:cache
	@echo "🧬 Rodando migrações e seeders..."
	$(ARTISAN) migrate --seed
	@echo "🎨 Instalando AdminLTE e UI..."
	$(ARTISAN) adminlte:install
	$(ARTISAN) ui bootstrap --auth
	npm install
	npm run dev
	@echo "🔐 Painel Admin com login criado!"
	@echo "🚀 Aplicação rodando em http://localhost:8000"

up: .env ## Sobe os containers
	@echo "🐳 Subindo containers Docker..."
	$(COMPOSE_UP)
	@sleep 5

.env:
	@if [ ! -f .env ]; then \
		echo "Copiando .env.example para .env..."; \
		cp .env.example .env; \
	fi

## --------------------------------------
## Gerenciamento do Ambiente
## --------------------------------------

down: ## Para e remove os containers
	@echo "🛑 Parando e removendo containers..."
	$(COMPOSE_DOWN)

stop: ## Apenas para os containers, sem remover
	@echo "⏸️ Parando containers..."
	docker-compose stop

logs: ## Mostra os logs de um serviço (ex: make logs service=app)
	@echo "📋 Logs do serviço: $(service)..."
	docker-compose logs -f $(service)

## --------------------------------------
## Comandos Auxiliares
## --------------------------------------

artisan: ## Executa um comando Artisan (ex: make artisan cmd="migrate:fresh")
	@echo "⚙️ Executando: php artisan $(cmd)"
	$(ARTISAN) $(cmd)

composer: ## Executa um comando Composer (ex: make composer cmd="require spatie/laravel-permission")
	@echo "🎼 Executando: composer $(cmd)"
	$(COMPOSER) $(cmd)

npm: ## Executa um comando npm no host (ex: make npm cmd="run dev")
	@echo "📦 Executando: npm $(cmd)"
	npm $(cmd)
