# Define o shell padrão
SHELL := /bin/bash

# Comandos Docker Compose
COMPOSE_UP        = docker-compose up --build -d
COMPOSE_DOWN      = docker-compose down
COMPOSE_EXEC_APP  = docker-compose exec app

# Comandos Artisan e Composer
ARTISAN = $(COMPOSE_EXEC_APP) php artisan
COMPOSER = $(COMPOSE_EXEC_APP) composer

# Silenciar saídas padrão
.SILENT:

# Alvos que não são arquivos
.PHONY: setup up down stop logs artisan composer npm

## --------------------------------------
## Gerenciamento do Ambiente
## --------------------------------------

up: .env  ## Sobe os containers (sem executar comandos do Laravel por padrão)
	@echo "🐳 Subindo containers Docker..."
	$(COMPOSE_UP)
	@sleep 10 # Dê um tempo para o MySQL inicializar
	@echo "🚀 Aplicação (e banco) subindo em segundo plano."
	@echo "Acesse http://localhost:8080 após o setup inicial, se já rodou."

down:      ## Para e remove os containers
	@echo "🛑 Parando e removendo containers..."
	$(COMPOSE_DOWN)

stop:      ## Apenas para os containers, sem remover
	@echo "⏸️ Parando containers..."
	docker-compose stop

logs:      ## Mostra os logs de um serviço (ex: make logs service=app)
	@echo "📋 Logs do serviço: $(service)..."
	docker-compose logs -f $(service)

## --------------------------------------
## Setup Inicial (executar apenas na primeira vez ou para reinstalação de dependências)
## --------------------------------------

setup: up  ## Executa a configuração inicial do projeto Laravel
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
	@echo "📦 Instalando dependências NPM (no host)..."
	npm install
	@echo "⚙️ Compilando assets (no host)..."
	npm run dev
	@echo "✅ Setup inicial do ambiente concluído! Aplicação rodando em http://localhost:8080"

.env:
	@if [ ! -f .env ]; then \
		echo "Copiando .env.example para .env..."; \
		cp .env.example .env; \
	fi

## --------------------------------------
## Comandos Auxiliares
## --------------------------------------

artisan:   ## Executa um comando Artisan (ex: make artisan cmd="migrate:fresh")
	@echo "⚙️ Executando: php artisan $(cmd)"
	$(ARTISAN) $(cmd)

composer:  ## Executa um comando Composer (ex: make composer cmd="require spatie/laravel-permission")
	@echo "🎼 Executando: composer $(cmd)"
	$(COMPOSER) $(cmd)

npm:       ## Executa um comando npm no host (ex: make npm cmd="run dev")
	@echo "📦 Executando: npm $(cmd)"
	npm $(cmd)
	
test:  ## Executa os testes com config:clear antes
	@echo "🧹 Limpando cache de configuração..."
	$(ARTISAN) config:clear
	@echo "✅ Rodando testes (ambiente .env.testing)..."
	APP_ENV=testing $(ARTISAN) test
