# Sales Laravel + Vue

Este projeto utiliza **Laravel** para o backend e **Vue 3 com Vite** para o frontend, tudo rodando dentro de containers **Docker**. O ambiente também inclui **Redis** para cache, **Mailhog** para teste de e-mails, além do uso de **jobs** e **scheduler** do Laravel.

---

## 🧱 Requisitos

-   [Docker](https://www.docker.com/)
-   [Docker Compose](https://docs.docker.com/compose/)
-   Opcional: [Laravel Sail](https://laravel.com/docs/sail)

---

## 🚀 Subindo o projeto

### 1. Clone o repositório

```bash
git clone https://github.com/douglas-alvarenga/sales-laravel-vue.git
cd sales-laravel-vue
```

## 2. Copie o arquivo `.env`

```bash
cp .env.example .env
```

Ajuste as variáveis conforme necessário, principalmente configurações de banco de dados, Redis, e-mail e filas.

## 3. Suba os containers

```bash
docker-compose build
```

```bash
docker-compose up -d
```

## 4. Rode as migrations

```bash
# Docker Compose
docker-compose exec app php artisan migrate --force
```

Uso de `--force` apenas para pular confirmação devido a aplicação estar setada como **produção**

## 5. Rode os seeders

O projeto já vem com um seeder inicial que:

-   Cria um **usuário padrão**:
    -   **Usuário:** `admin`
    -   **Email:** `admin@teste.com`
    -   **Senha:** `1234`
-   Gera um número configurável de **vendedores** e **vendas** para popular a base de dados.

**`Observação:`** foi optado por deixar esses cadastros "fake" diretamente no seeder principal para facilitar testes devido a esse projeto ser apenas demonstrativo.
Caso ele venha a ser usado em produção no futuro, deve-se remover as chamadas dos fakers de `DatabaseSeeder`

Você pode controlar a quantidade de registros usando variáveis de ambiente:

```env
SELLER_SEEDER_COUNT=20     # Quantidade de vendedores a serem criados
SALE_SEEDER_COUNT=100      # Quantidade de vendas a serem criadas
```

Se os valores não forem definidos, o sistema usará 10 como padrão.

Executar o seeder:

```bash
# Docker Compose
docker-compose exec app php artisan db:seed --force
```

Uso de `--force` apenas para pular confirmação devido a aplicação estar setada como **produção**

## ⏱️ Tarefas em segundo plano (Jobs e Scheduler)

O projeto utiliza:

-   **Jobs em segundo plano** (`queue:work`)
-   **Agendamentos periódicos** (`schedule:work`)

🔁 Caso o container não execute automaticamente esses processos, realizar execução manual:

```bash
# Executar os jobs
docker exec app php artisan queue:work

# Executar o scheduler
docker exec app php artisan schedule:work
```

## 📬 Testes de e-mail com Mailhog

Mailhog está disponível localmente em:

```
http://localhost:8025
```

Todos os e-mails enviados em ambiente de desenvolvimento serão capturados por ele.

## 🧠 Cache com Redis

Redis é utilizado como driver de cache. Certifique-se de que o .env está configurado assim:

```env
CACHE_DRIVER=redis
REDIS_HOST=redis
```

## 🌐 Acesso à aplicação

A URL local será definida no docker-compose.yml. Por padrão:

```
http://localhost
```

Ou:

```
http://localhost:8000
```

## 📦 Estrutura do projeto

-   `app`/ – Backend Laravel
-   `resources/js/` – Frontend Vue 3 (com Vite)
-   `docker/` ou arquivos `docker-compose.yml` – Configuração dos containers
