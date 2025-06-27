# Laravel AdminLTE v3 Starter

🚀 Projeto base para aplicações administrativas em Laravel 10 com **AdminLTE 3** totalmente integrado. Ideal para iniciar rapidamente o desenvolvimento de painéis administrativos modernos, com um ambiente pronto para desenvolvimento local usando **Docker** e um **Makefile** com comandos automatizados.

## 🧰 Tecnologias Utilizadas

- **Laravel 10**
- **AdminLTE 3** (via `jeroennoten/laravel-adminlte`)
- **PHP 8.2+**
- **MySQL 8**
- **Docker + Docker Compose**
- **Makefile** (automação de setup e execução)

---

## 📁 Estrutura do Projeto

Este projeto já vem com:

- Laravel 10 instalado e configurado
- Pacote AdminLTE pré-configurado
- Docker Compose para ambiente de desenvolvimento local
- Makefile com comandos para facilitar o uso
- CRUD básico de *Produtos* para demonstração
- CRUD básico de usuários para administradores
- Configuração de autenticação padrão
- Migrações e seeders para usuários

---

## 🚀 Como Começar

### 1. Pré-requisitos

- [Docker](https://www.docker.com/)
- [Make](https://www.gnu.org/software/make/)
- [Node.js e NPM](https://nodejs.org/)

---

### 2. Subir o Projeto

Execute no terminal:

```bash
make setup
```

Esse comando irá:

- Subir os containers com Docker
- Instalar dependências do Composer
- Criar o arquivo `.env` se necessário
- Gerar a chave da aplicação
- Limpar e cachear as configurações
- Rodar as migrações e seeders
- Instalar dependências do NPM
- Compilar os assets

Após finalizado, a aplicação estará disponível em: [http://localhost:8080](http://localhost:8080)

**Usuário Padrão:** `admin@admin.com`  
**Senha:** `password`

---

## 🛠️ Comandos Úteis

Todos os comandos são executados via `make`.

| Comando                | Descrição                                               |
|------------------------|--------------------------------------------------------|
| `make setup`           | Realiza a configuração inicial do projeto              |
| `make up`              | Sobe os containers Docker                              |
| `make down`            | Para e remove os containers Docker                     |
| `make stop`            | Apenas para os containers (sem remover)                |
| `make logs`            | Exibe os logs do serviço (`make logs service=app`)     |
| `make artisan`         | Executa comandos do Artisan (`make artisan cmd="migrate"`) |
| `make composer`        | Executa comandos do Composer (`make composer cmd="install"`) |
| `make npm`             | Executa comandos do NPM (`make npm cmd="run dev"`)     |
| `make test`            | Executa os testes do PHPUnit                           |

---

## 🧑‍💻 Contribuindo

Sinta-se à vontade para clonar este projeto e adaptá-lo conforme as necessidades da sua aplicação.

```bash
git clone https://github.com/seu-usuario/laravel-adminlte-v3-starter.git
cd laravel-adminlte-v3-starter
make setup
```

---

❤️ **Créditos**  
Este projeto utiliza:

- [Laravel](https://laravel.com/)
- [AdminLTE](https://adminlte.io/)
- [Laravel-AdminLTE](https://github.com/jeroennoten/Laravel-AdminLTE)
