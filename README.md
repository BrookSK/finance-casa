# FinançasCasal

Sistema web de controle financeiro pessoal e de casal. Mobile-first, PWA, com visão compartilhada da casa e visão individual de cada pessoa.

## Requisitos

- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.3+
- Apache com mod_rewrite habilitado (ou Nginx equivalente)

## Instalação

### 1. Banco de dados

Rode os scripts SQL na ordem abaixo no seu MySQL:

```sql
source database/schema.sql;
source database/seed.sql;
source database/seed_data.sql;
```

### 2. Configuração do banco

Edite o arquivo `app/core/Database.php` e ajuste as credenciais:

```php
private static string $host = 'localhost';
private static string $dbname = 'financas_casal';
private static string $username = 'root';
private static string $password = '';
```

### 3. Servidor web

**Opção A — Apache apontando para a raiz do projeto:**

O `.htaccess` da raiz redireciona tudo para `public/index.php`. Basta apontar o VirtualHost para a pasta raiz do projeto.

**Opção B — Apache apontando para `/public`:**

Aponte o DocumentRoot diretamente para a pasta `public/`. O `.htaccess` interno cuida do roteamento.

**Opção C — PHP built-in (desenvolvimento):**

```bash
php -S localhost:8000 -t public
```

### 4. Acesso

Abra o navegador e acesse o endereço configurado.

**Usuários padrão:**

| Usuário | E-mail              | Senha    | Papel         |
|---------|---------------------|----------|---------------|
| Lucas   | lucas@financas.com  | password | Administrador |
| Bia     | bia@financas.com    | password | Usuário       |

> Troque as senhas após o primeiro login em **Configurações**.

Para gerar um novo hash de senha via terminal:

```bash
php database/generate_password.php minhasenha
```

## Estrutura do projeto

```
├── index.php                  # Entry point (raiz)
├── .htaccess                  # Rewrite para raiz
├── public/
│   ├── index.php              # Front controller
│   ├── .htaccess              # Rewrite interno
│   ├── manifest.json          # PWA manifest
│   ├── sw.js                  # Service Worker
│   └── assets/
│       ├── css/app.css        # Estilos (design system completo)
│       ├── js/app.js          # JavaScript principal
│       └── img/               # Ícones PWA
├── app/
│   ├── core/                  # Database, Router, Model base, Controller base, CSRF
│   ├── controllers/           # Todos os controllers
│   ├── models/                # Todos os models
│   ├── middleware/             # Autenticação
│   ├── helpers/               # Funções globais
│   ├── routes/web.php         # Definição de rotas
│   └── views/                 # Templates PHP
│       ├── layouts/main.php   # Layout principal (sidebar + bottom nav)
│       ├── auth/              # Login
│       ├── dashboard/         # Dashboard
│       ├── timeline/          # Linha do tempo financeira
│       ├── receitas/          # CRUD receitas
│       ├── despesas/          # CRUD despesas
│       ├── cofrinhos/         # Cofrinhos / envelopes
│       ├── cartoes/           # Cartões de crédito
│       ├── faturas/           # Faturas
│       ├── listas/            # Lista de compras inteligente
│       ├── orcamentos/        # Orçamentos por categoria
│       ├── relatorios/        # Gráficos e relatórios
│       ├── notificacoes/      # Central de notificações
│       ├── config/            # Configurações
│       └── errors/            # 404
└── database/
    ├── schema.sql             # Criação das tabelas (NÃO EDITAR)
    ├── seed.sql               # Dados iniciais (usuários, categorias, cartões)
    ├── seed_data.sql          # Receitas, despesas, cofrinhos, orçamentos
    ├── generate_password.php  # Gerador de hash bcrypt
    └── migrations/            # Alterações futuras no banco
```

## Funcionalidades

### Fase 1 — MVP
- Login com sessão segura, CSRF, bcrypt
- Dashboard com cards financeiros, status do mês, atalhos rápidos
- Timeline financeira cronológica com saldo acumulado
- Cofrinhos com depósito rápido, barra de progresso, histórico
- Receitas e Despesas (CRUD completo)
- Cartões de crédito com visual de cartão
- Faturas

### Fase 2 — Uso real
- Lista de Compras Inteligente com controle de orçamento em tempo real
- Orçamentos por categoria com alertas
- Notificações automáticas (vencimentos, atrasos, cofrinhos, orçamentos)
- Relatórios com gráficos (Chart.js)

### Fase 3 — Refinamento
- Modo escuro
- Exportação CSV (receitas, despesas, cofrinhos)
- Tela de configurações (usuários, categorias)
- PWA com Service Worker

## Segurança

- Senhas com hash bcrypt (`password_hash`)
- Proteção CSRF em todos os formulários
- Prepared statements (PDO) contra SQL Injection
- `htmlspecialchars` contra XSS
- Sessão regenerada no login
- Headers de segurança via `.htaccess`

## Permissões

- **Lucas (admin):** acesso total — cadastrar, editar, excluir tudo
- **Bia (usuário):** visualiza dados compartilhados, edita os próprios dados, alimenta cofrinhos

## Migrations

Nunca edite o `schema.sql`. Para alterações no banco, crie um arquivo em `database/migrations/` com nome sequencial:

```
database/migrations/001_add_campo_exemplo.sql
```
