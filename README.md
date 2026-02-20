# 🎫 My Tickets - Backend (API)

Este é o repositório backend do **My Tickets**, um sistema SaaS de chamados (Helpdesk/Ticketing) multi-tenant. A aplicação foi desenvolvida como uma API RESTful utilizando Laravel, desenhada para ser consumida por um frontend SPA (Single Page Application).

## 🚀 Tecnologias e Ferramentas

- **Framework:** Laravel 11 (PHP 8.2+)
- **Autenticação:** Laravel Sanctum (Autenticação baseada em Tokens para SPA)
- **Controle de Acesso:** Spatie Laravel Permission (Roles e Permissions)
- **Qualidade de Código:** PHPStan (Análise Estática Nível Máximo) + Husky (Pre-commit hooks)
- **Testes de E-mail:** Mailtrap / Mailpit

## 🏗️ Arquitetura

O projeto segue princípios de **Clean Architecture** e **DDD (Domain-Driven Design)** para manter o código testável, escalável e de fácil manutenção:

- **Domains:** Lógica agrupada por contexto de negócio (ex: `Identity` para Usuários, Tenants e Autenticação).
- **Service Pattern:** Regras de negócio isoladas em classes de Serviço (`RegisterCustomerService`, `LoginService`).
- **API Resources:** Formatação de payload de saída centralizada (`AuthUserResource`).
- **Form Requests:** Validação rigorosa de dados de entrada.

## ✨ Funcionalidades (Atuais)

### Domínio de Identidade (Identity & Auth)

- [x] **Registro de Tenant (SaaS):** Criação simultânea de Empresa (Customer), Unidade de Negócio (Matriz) e Usuário Admin (Manager).
- [x] **Login / Logout:** Autenticação via Sanctum retornando token e dados formatados (incluindo permissões do usuário para a UI).
- [x] **Recuperação de Senha:** Fluxo de "Esqueci minha senha" e "Reset" adaptado para apontar para rotas do Frontend (Vue.js).
- [x] **Verificação de E-mail:** Envio e validação de links de confirmação de e-mail integrados com a SPA.
- [x] **ACL (Controle de Acesso):** Perfis pré-configurados (`dev`, `manager`, `agent`, `user`) e interceptação global (`Gate::before`) para super-administradores.

## 🐳 Como rodar o projeto localmente (com Laravel Sail)

Este projeto utiliza o **Laravel Sail**, uma interface leve de linha de comando para interagir com o ambiente Docker padrão do Laravel.

1.  **Clone o repositório:**

    ```bash
    git clone https://github.com/renancaldasdev/my_tickets_backend

    cd my-tickets-backend
    ```

2.  **Instal as dependências:**
    ```bash
    composer install
    ```
3.  **Configuração de ambiente:**

    Copie o arquivo .env.example para .env e configure suas variáveis (Banco de Dados, Mailtrap, e URL do Frontend):

    ```bash
    cp .env.example .env
    ```

4.  **Suba os containers do Sail:**
    ```
    ./vendor/bin/sail up -d
    ```
5.  **Gere a chave da aplicação e rode as migrações/seeders:**

        Com os containers rodando, execute:

    ```bash
    ./vendor/bin/sail artisan key:generate

    ./vendor/bin/sail artisan migrate --seed
    ```

## 💡 Dica de Uso do Sail

Para não precisar digitar ./vendor/bin/sail toda vez, você pode criar um alias no seu terminal:

```bash
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
```

## 🔒 Qualidade de Código e Git Hooks

Para garantir que o repositório se mantenha limpo e padronizado, utilizamos Husky integrado com PHPStan (nível máximo) e Laravel Pint.

- Pre-commit Hook: Sempre que você executar um git commit, o Husky entrará em ação automaticamente. Ele formata o código (Pint) e roda a análise estática (PHPStan). Se o código não estiver no padrão ou tiver erros de tipagem, o commit será bloqueado até que você corrija os problemas.

- Pre-push Hook: Antes de enviar o código para o GitHub (git push), o Husky roda a suíte de testes (Pest/PHPUnit) para garantir que as novas alterações não quebraram nenhuma funcionalidade existente.

## Comandos úteis para análise manual (usando Sail):

```bash
# Rodar análise do PHPStan
./vendor/bin/sail bin phpstan analyse --memory-limit=2G

# Rodar formatação de código (Pint)
./vendor/bin/sail bin pint

# Rodar os testes
./vendor/bin/sail artisan test
```
