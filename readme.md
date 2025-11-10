# BeTalent - Teste Técnico (API Laravel)

Este projeto foi desenvolvido como parte do processo seletivo para a vaga de Desenvolvedor Laravel na **BeTalent**.  
A aplicação segue os requisitos do teste técnico, implementando uma **API RESTful** em Laravel com autenticação, controle de acesso e reembolso de despesas.

---

## 🚀 Tecnologias Utilizadas / Requisitos

- **PHP 8.3**
- **Laravel 10**
- **MySQL**
- **Composer**
- **Docker** (para rodar os mocks)
- **Postman** (para testes das rotas)

---

## 🧩 Implementações Realizadas

### Nível escolhido: **2**
- Implementação de rotas REST
- CRUD completo da entidade `User`
- Retorno em formato **JSON**
- Gateways com autenticação
- Migrações e Seeders para dados iniciais
- Autenticação com tokens (`Laravel Sanctum`)
- CRUD de **reembolsos** associado ao usuário autenticado
- Cálculo do valor total baseado em múltiplos produtos
- Collection do Postman com todos os endpoints disponíveis

---

## ⛔ Implementações Não Realizadas

- Testes automatizados (TDD)
- Autorização por **roles**
- Docker Compose com MySQL, aplicação Laravel e mocks integrados

---

## ✴️ Relato sobre o Desenvolvimento

O projeto foi desenvolvido ao longo de uma semana, envolvendo:
- Estudo da estrutura do desafio  
- Preparação do ambiente  
- Implementação e teste das rotas via Postman  

Optei por usar o **Laravel 10**, que possui uma ótima separação de rotas e é, na minha experiência, a versão mais estável e prática para construir APIs REST.  
A autenticação foi feita com **Laravel Sanctum**, embora eu tenha mais familiaridade com **JWT**.

Tive dificuldades iniciais na comunicação entre os gateways e minha API, mas isso foi resolvido após revisar o enunciado com mais atenção. A maior parte dos problemas foi solucionada relendo cuidadosamente as instruções.

---
💡 Após rodar a aplicação completa (MySQL, Laravel e Mocks), importe a collection Postman que está na raiz do projeto.
Use a rota de login para obter um token e adicione-o no header de cada requisição com o prefixo auth_.


## ⚙️ Como Rodar o Projeto

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

# Iniciar o servidor Laravel
php artisan serve

# Rodar os mockups (com autenticação)
docker run -p 3001:3001 -p 3002:3002 matheusprotzen/gateways-mock