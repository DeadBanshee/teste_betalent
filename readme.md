# BeTalent - Teste Técnico (API Laravel)

Este projeto foi desenvolvido como parte do processo seletivo para a vaga de Desenvolvedor Laravel na **BeTalent**.  
A aplicação segue os requisitos do teste técnico, implementando uma **API RESTful** em Laravel com autenticação, controle de acesso, e reembolso de despesas.

---

## 🚀 Tecnologias Utilizadas

- **PHP 8.3**
- **Laravel 10**
- **MySQL**
- **Composer**
- **Docker**
- **Postman** (para testes das rotas)

---

## 🧩 Estrutura dos Níveis

### 🟢 Nível 1 - CRUD de Usuários
- Implementação de rotas REST
- CRUD completo da entidade `User`
- Retorno em formato **JSON**
- Migrações e Seeders para dados iniciais

### 🟡 Nível 2 - Controle de Acesso e Reembolsos
- Autenticação com tokens (`Laravel Sanctum`)
- CRUD de **reembolsos** associado ao usuário autenticado
---

## ⚙️ Como Rodar o Projeto

### 🔹 Opção 1: Localmente (sem Docker)
```bash
# Clonar o repositório
git clone https://github.com/DeadBanshee/teste_betalent
cd teste_betalent

# Instalar dependências
composer install

# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Criar banco de dados e configurar .env
DB_DATABASE=be_talent
DB_USERNAME=root
DB_PASSWORD=

# Rodar migrações e seeders
php artisan migrate --seed

# Iniciar o servidor
php artisan serve