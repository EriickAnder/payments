# Projeto Checkout com Laravel 9 e Asaas

Este projeto simula uma tela de checkout e vendas utilizando Laravel 9 integrado com a API Asaas.

---

## Pré-requisitos

- PHP 8.x
- Composer
- MySQL
- Laravel 9

---

## Configuração do ambiente

1. Clone o repositório.
2. Configure o banco de dados local no .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pagamento
DB_USERNAME=root
DB_PASSWORD=root
3. composer install
4. php artisan migrate
5. Configure o token da API Asaas no .env:
TOKEN_ASAAS=seu_token_aqui
6. php artisan serve  

## http://localhost:8000



Testes realizados
Validação de CPF

Pagamento realizado via Asaas
