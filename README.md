<div align="center">
  <img src="public/images/k9.png" alt="K9 Logo" width="200"/>
  
  # K9
  ### Plataforma de gestão de pentests para Red Teams
</div>

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](https://docker.com)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

> **Sistema profissional de gestão de pentests e vulnerabilidades** desenvolvido para equipes Red Team, com foco em organização, rastreabilidade e controle de testes de penetração.

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Funcionalidades](#-funcionalidades)
- [Stack Tecnológica](#-stack-tecnológica)
- [Arquitetura](#-arquitetura)
- [Instalação](#-instalação)
- [Configuração](#-configuração)
- [Uso](#-uso)
- [API](#-api)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Contribuindo](#-contribuindo)
- [Licença](#-licença)

## 🎯 Sobre o Projeto

**K9** é uma plataforma completa de gerenciamento de pentests e vulnerabilidades, desenvolvida especificamente para equipes Red Team e profissionais de segurança ofensiva. O sistema permite:

- **Gestão completa de pentests** com controle de prazos e responsáveis
- **Rastreamento de vulnerabilidades** por criticidade e status
- **Documentação estruturada** de descobertas e evidências
- **Controle granular de acesso** (ACL)
- **Relatórios profissionais** para apresentação aos clientes
- **APIs seguras** para integração com outras ferramentas

## ✨ Funcionalidades

### 🎯 Gestão de Pentests
- **Cadastro completo de pentests** com informações detalhadas
- **Controle de prazos** (data início, finalização e deadline)
- **Priorização** (Urgente, Alta, Média, Baixa)
- **Status automático** (Aguardando Início, Em Andamento, Finalizado, Atrasado)
- **Atribuição de responsáveis**
- **Versionamento** de aplicações testadas

### 🐛 Gestão de Vulnerabilidades
- **Classificação por criticidade** (Crítica, Alta, Média, Baixa, Informativa)
- **Rastreamento de status** (Sanada, Não Sanada)
- **Documentação técnica** completa
- **Evidências e observações**
- **Vinculação com pentests**
- **Ordenação inteligente** nas listagens

### 🔐 Segurança
- **Autenticação robusta** com Laravel Sanctum
- **Sistema de permissões** (Spatie Laravel Permission)
- **JWT Authentication** para APIs
- **Controle de acesso granular**
- **Auditoria de ações**
- **Soft Deletes** em todos os modelos

### 🎨 Interface
- **AdminLTE 3** - Interface administrativa moderna
- **Bootstrap 5** - Framework CSS responsivo
- **DataTables Server-side** - Tabelas otimizadas com ordenação avançada
- **Design intuitivo** focado em produtividade
- **Tema dark** para longas jornadas de trabalho

### 📊 Relatórios e Métricas
- **Dashboard com estatísticas** de pentests e vulnerabilidades
- **Gráficos de criticidade** das vulnerabilidades
- **Indicadores de progresso** e prazos
- **Exportação** em múltiplos formatos (PDF, Excel, CSV)
- **Visão consolidada** por projeto

### 🚀 Performance
- **Laravel Vite** - Build tool otimizado
- **Minificação de assets** - Otimização de recursos
- **Redis Cache** - Cache de alta performance
- **Query optimization** - Consultas otimizadas ao banco

## 🛠️ Stack Tecnológica

### Backend
- **Laravel 12** - Framework PHP
- **PHP 8.2+** - Linguagem de programação
- **MySQL 8** - Banco de dados
- **Redis** - Cache e sessões
- **Docker/Sail** - Containerização

### Frontend
- **Bootstrap 5** - Framework CSS
- **AdminLTE 3** - Template administrativo
- **Vite** - Build tool
- **DataTables** - Tabelas avançadas
- **SASS** - Pré-processador CSS

### Ferramentas de Desenvolvimento
- **Laravel Sail** - Docker environment
- **Laravel Pint** - Code style fixer
- **PEST** - Testing framework
- **Laravel Debugbar** - Debug toolbar

## 🏗️ Architecture

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Administrative controllers
│   │   ├── Api/            # REST APIs
│   │   └── Auth/           # Authentication
│   ├── Models/             # Eloquent models
│   └── Providers/          # Service providers
├── resources/
│   ├── views/              # Blade views
│   ├── js/                 # JavaScript
│   └── sass/               # SASS styles
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
└── database/
    ├── migrations/          # Migrations
    └── seeders/            # Seeders
```

## 🚀 Instalação

### Pré-requisitos
- **Docker** e **Docker Compose**
- **Node.js** 18+ (para desenvolvimento local)
- **Composer** (para desenvolvimento local)

### Passo a Passo

1. **Clone o repositório**
```bash
git clone <repository-url>
cd pentest
```

2. **Prepare o ambiente**
```bash
cp .env.example .env
```

3. **Configure as variáveis de ambiente**
```bash
# Edite o arquivo .env com suas configurações
nano .env
```

4. **Instale as dependências**
```bash
composer install
npm install
```

5. **Configure o Laravel**
```bash
php artisan key:generate
php artisan jwt:secret
```

6. **Configure o Docker (opcional)**
```bash
# Alias para Laravel Sail
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

7. **Execute as migrations**
```bash
# Com Docker
sail artisan migrate --seed

# Sem Docker
php artisan migrate --seed
```

8. **Configure o storage**
```bash
# Com Docker
sail artisan storage:link

# Sem Docker
php artisan storage:link
```

9. **Compile os assets**
```bash
# Desenvolvimento
npm run dev

# Produção
npm run build
```

## ⚙️ Configuração

### Variáveis de Ambiente Importantes

```env
# Application
APP_NAME="K9"
APP_DES="Plataforma de gestão de pentests para Red Teams"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=pentest
DB_USERNAME=pentest
DB_PASSWORD=pentest

# JWT
JWT_SECRET=your-jwt-secret
JWT_TTL=60

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Docker Compose

O projeto inclui configuração completa do Docker com:

- **Laravel Sail** - Container principal
- **MySQL 8** - Banco de dados
- **Redis** - Cache e sessões
- **Mailpit** - Teste de e-mails

## 🎮 Uso

### Acesso ao Sistema

**Credenciais padrão:**
- **Email:** programador@base.com
- **Password:** 12345678

### Comandos Úteis

```bash
# Desenvolvimento
sail up -d                    # Iniciar containers
sail artisan serve            # Servidor de desenvolvimento
npm run dev                   # Compilar assets (dev)

# Produção
npm run build                 # Compilar assets (prod)
php artisan config:cache      # Cache de configuração
php artisan route:cache       # Cache de rotas

# Manutenção
sail artisan migrate          # Executar migrations
sail artisan db:seed          # Executar seeders
sail artisan storage:link     # Link simbólico do storage
sail bin pint                 # Corrigir code style
```

### Módulos Principais

#### Pentests
- **Listagem:** `/admin/pentests` - Lista todos os pentests com filtros e ordenação
- **Cadastro:** `/admin/pentests/create` - Criar novo pentest
- **Visualização:** `/admin/pentests/{id}` - Ver detalhes e estatísticas
- **Edição:** `/admin/pentests/{id}/edit` - Editar pentest existente

#### Vulnerabilidades
- **Listagem:** `/admin/vulnerabilities` - Lista todas as vulnerabilidades
- **Cadastro:** Através do botão no pentest específico
- **Visualização:** `/admin/vulnerabilities/{id}` - Ver detalhes completos
- **Edição:** `/admin/vulnerabilities/{id}/edit` - Editar vulnerabilidade

## 🔌 API

### Autenticação JWT

```bash
# Login
POST /api/v1/login
{
  "email": "user@example.com",
  "password": "password"
}

# Registro
POST /api/v1/register
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password"
}

# Refresh Token
POST /api/v1/refresh
Authorization: Bearer {token}
```

### Endpoints Disponíveis

- `GET /api/v1/admin/pentests` - Listar pentests
- `GET /api/v1/admin/pentests/{id}` - Detalhes do pentest
- `GET /api/v1/admin/vulnerabilities` - Listar vulnerabilidades
- `GET /api/v1/admin/profile` - Perfil do usuário
- `POST /api/v1/logout` - Logout

## 📁 Estrutura do Projeto

### Diretórios Principais

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/           # Controllers administrativos
│   │   │   ├── PentestController.php
│   │   │   ├── VulnerabilityController.php
│   │   │   └── ACL/         # Controle de acesso
│   │   ├── Api/             # REST APIs
│   │   └── Auth/            # Autenticação
│   ├── Models/              # Models Eloquent
│   │   ├── Pentest.php
│   │   ├── Vulnerability.php
│   │   └── User.php
│   ├── Helpers/             # Helpers customizados
│   │   ├── CheckPermission.php
│   │   └── TextProcessor.php
│   └── Providers/           # Service providers
├── resources/
│   ├── views/
│   │   ├── admin/           # Views administrativas
│   │   │   ├── pentests/
│   │   │   └── vulnerabilities/
│   │   ├── auth/            # Views de autenticação
│   │   └── layouts/         # Layouts base
│   ├── js/                  # JavaScript
│   └── sass/                # Estilos SASS
├── routes/
│   ├── web.php              # Rotas web
│   └── api.php              # Rotas da API
└── database/
    ├── migrations/           # Migrations
    └── seeders/             # Seeders
```

## 🤝 Contribuindo

### Padrões de Código

- **PSR-12** - Padrão de codificação PHP
- **Laravel Pint** - Corretor de estilo de código
- **PEST** - Framework de testes
- **Conventional Commits** - Padrão de commits

### Workflow

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'feat: Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está licenciado sob a **MIT License** - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 🙏 Agradecimentos

- [Laravel](https://laravel.com) - Framework PHP
- [AdminLTE](https://adminlte.io) - Template administrativo
- [Spatie](https://spatie.be) - Pacotes Laravel
- [Yajra DataTables](https://github.com/yajra/laravel-datatables) - DataTables para Laravel

---

<div align="center">
  <strong>Desenvolvido com ❤️ para profissionais de Red Team</strong>
  
  K9 - Seu guardião na gestão de pentests
</div>
