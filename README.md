# FatFree API - JWT + Refresh Tokens

Scaffold completo com:
- Fat-Free Framework 3.x
- JWT access token + refresh token
- Roles & Permissions (many-to-many)
- CRUD endpoints para users, roles, permissions, user-roles

## Instalação
- Ajuste `config/config.php` com suas credenciais
- `composer install`
- Importe `database/schema.sql` no MySQL
- Aponte seu servidor para o diretório com `index.php`

## Endpoints principais
- POST /auth/login { email, password }
- POST /auth/refresh { refresh_token }
- POST /auth/logout { refresh_token }
- CRUD /users, /roles, /permissions
- CRUD assignments /user-roles
