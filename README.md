# 📚 Sistema de Biblioteca

Um sistema completo de gerenciamento de biblioteca desenvolvido em Laravel, com interface moderna e funcionalidades avançadas para controle de empréstimos, alunos e acervo.

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/Docker-Ready-blue?style=for-the-badge&logo=docker" alt="Docker">
</p>

## 🚀 Funcionalidades

### 👥 **Gestão de Alunos**
- ✅ Cadastro completo com validações
- ✅ Busca avançada por nome, matrícula ou curso
- ✅ Histórico de empréstimos por aluno
- ✅ Interface responsiva e intuitiva

### 📖 **Gestão de Livros**
- ✅ Controle de acervo com ISBN
- ✅ Gestão de quantidade disponível/total
- ✅ Alertas de estoque baixo
- ✅ Busca por título, autor ou ISBN

### 🔄 **Sistema de Empréstimos**
- ✅ **Busca dinâmica** para alunos e livros
- ✅ Controle automático de disponibilidade
- ✅ Prazo de 15 dias configurável
- ✅ Alertas de empréstimos em atraso
- ✅ Histórico completo de empréstimos

### 🎨 **Interface Moderna**
- ✅ Design responsivo com Bootstrap 5
- ✅ **Sistema de notificações** avançado
- ✅ **Validações JavaScript** em tempo real
- ✅ Confirmações elegantes para exclusões
- ✅ Dashboard com estatísticas
- ✅ Ranking dos alunos mais leitores

### 🔧 **Recursos Técnicos**
- ✅ Autenticação segura
- ✅ Validações server-side e client-side
- ✅ Cache com Redis
- ✅ Logs detalhados
- ✅ Backup automático
- ✅ Ambiente Docker completo

## 🐳 Instalação com Docker (Recomendado)

### Pré-requisitos
- Docker (20.10+)
- Docker Compose (2.0+)
- Git

### 🚀 Instalação Rápida

```bash
# 1. Clonar o repositório
git clone <url-do-repositorio>
cd sistema-biblioteca

# 2. Instalar automaticamente
make install

# 3. Acessar a aplicação
# http://localhost:8080
```

### 📋 Credenciais Padrão
- **Email:** admin@biblioteca.com
- **Senha:** admin123

### 🌐 Serviços Disponíveis
| Serviço | URL | Descrição |
|---------|-----|-----------|
| **Aplicação** | http://localhost:8080 | Sistema principal |
| **phpMyAdmin** | http://localhost:8081 | Interface do banco |
| **Mailhog** | http://localhost:8025 | Captura de emails |

### 🛠️ Comandos Úteis

```bash
# Gerenciamento básico
make start          # Iniciar containers
make stop           # Parar containers
make restart        # Reiniciar containers
make logs           # Ver logs da aplicação

# Desenvolvimento
make shell          # Acessar container da aplicação
make shell-db       # Acessar MySQL
make test           # Executar testes

# Banco de dados
make migrate        # Executar migrações
make seed           # Executar seeders
make fresh          # Recriar banco com dados

# Manutenção
make backup         # Criar backup do banco
make restore FILE=x # Restaurar backup
make cache-clear    # Limpar cache
make clean          # Limpar tudo e recomeçar
```

## 💻 Instalação Manual (Sem Docker)

### Pré-requisitos
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 18+
- Redis (opcional)

### Passos de Instalação

```bash
# 1. Clonar repositório
git clone <url-do-repositorio>
cd sistema-biblioteca

# 2. Instalar dependências PHP
composer install

# 3. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 4. Configurar banco de dados no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_biblioteca
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# 5. Executar migrações e seeders
php artisan migrate --seed

# 6. Criar link simbólico para storage
php artisan storage:link

# 7. Instalar dependências Node.js (se houver)
npm install
npm run build

# 8. Iniciar servidor
php artisan serve
```

## 🗄️ Configuração do Banco de Dados

### MySQL
```sql
CREATE DATABASE sistema_biblioteca CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'biblioteca_user'@'localhost' IDENTIFIED BY 'sua_senha';
GRANT ALL PRIVILEGES ON sistema_biblioteca.* TO 'biblioteca_user'@'localhost';
FLUSH PRIVILEGES;
```

### Variáveis de Ambiente (.env)
```env
APP_NAME="Sistema de Biblioteca"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistema_biblioteca
DB_USERNAME=biblioteca_user
DB_PASSWORD=sua_senha

# Cache (opcional - Redis)
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Email (configurar conforme necessário)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
```

## 🎯 Como Usar o Sistema

### 1. **Primeiro Acesso**
- Acesse http://localhost:8080
- Faça login com: admin@biblioteca.com / admin123
- Explore o dashboard com estatísticas

### 2. **Cadastrar Alunos**
- Menu "Alunos" → "Novo Aluno"
- Preencha: nome, email, matrícula, curso, série
- Validações automáticas garantem dados corretos

### 3. **Cadastrar Livros**
- Menu "Livros" → "Novo Livro"
- Preencha: título, autor, ISBN, editora, ano, quantidades
- Sistema controla automaticamente disponibilidade

### 4. **Registrar Empréstimos**
- Menu "Empréstimos" → "Novo Empréstimo"
- **Use a busca dinâmica** para encontrar aluno e livro
- Sistema verifica disponibilidade automaticamente
- Prazo padrão: 15 dias

### 5. **Gerenciar Devoluções**
- Menu "Empréstimos Ativos"
- Clique no botão de devolução
- Sistema atualiza disponibilidade automaticamente

### 6. **Monitorar Atrasos**
- Menu "Em Atraso" mostra empréstimos vencidos
- Dashboard exibe alertas de atrasos
- Notificações automáticas

## 🔧 Configurações Avançadas

### Personalizar Prazos
```env
# .env
BIBLIOTECA_PRAZO_EMPRESTIMO=15  # dias
BIBLIOTECA_MAX_EMPRESTIMOS_POR_ALUNO=3
BIBLIOTECA_MULTA_POR_DIA=2.00   # valor em reais
```

### Cache e Performance
```bash
# Otimizar para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Com Docker
make cache-optimize
```

### Backup Automático
```bash
# Backup manual
make backup

# Agendar backup diário (crontab)
0 2 * * * cd /path/to/projeto && make backup
```

## 🧪 Testes

```bash
# Com Docker
make test

# Manual
php artisan test

# Com coverage
php artisan test --coverage
```

## 📊 Monitoramento

### Logs
```bash
# Ver logs em tempo real
make logs

# Logs específicos
tail -f storage/logs/laravel.log
```

### Métricas
- Dashboard com estatísticas em tempo real
- Ranking de alunos mais leitores
- Alertas de estoque baixo
- Controle de empréstimos em atraso

## 🚀 Deploy para Produção

### Com Docker
```bash
# Usar configuração de produção
docker-compose -f docker-compose.prod.yml up -d

# Ou usar Makefile
make prod-deploy
```

### Manual
```bash
# Configurar ambiente
APP_ENV=production
APP_DEBUG=false

# Otimizar
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configurar servidor web (Nginx/Apache)
# Apontar para pasta public/
```

## 🛡️ Segurança

- ✅ Autenticação Laravel Sanctum
- ✅ Validações CSRF
- ✅ Sanitização de dados
- ✅ Proteção contra SQL Injection
- ✅ Headers de segurança configurados
- ✅ Logs de auditoria

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📝 Changelog

### v1.0.0 (2024-01-01)
- ✅ Sistema completo de biblioteca
- ✅ Busca dinâmica para empréstimos
- ✅ Interface moderna e responsiva
- ✅ Sistema de notificações avançado
- ✅ Validações JavaScript em tempo real
- ✅ Ambiente Docker completo
- ✅ Dashboard com estatísticas
- ✅ Sistema de backup automático

## 🐛 Solução de Problemas

### Problemas Comuns

#### Erro de Permissão
```bash
# Docker
make shell
chown -R www-data:www-data /var/www/storage
chmod -R 775 /var/www/storage

# Manual
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/
```

#### Erro de Conexão com Banco
```bash
# Verificar se MySQL está rodando
docker-compose ps  # Docker
sudo systemctl status mysql  # Manual

# Testar conexão
php artisan tinker
>>> DB::connection()->getPdo();
```

#### Cache Corrompido
```bash
make cache-clear  # Docker
php artisan optimize:clear  # Manual
```

### Logs de Debug
```bash
# Ver logs detalhados
make logs  # Docker
tail -f storage/logs/laravel.log  # Manual
```

## 📞 Suporte

- 📧 Email: suporte@biblioteca.com
- 🐛 Issues: [GitHub Issues](link-para-issues)
- 📖 Documentação: [Wiki do Projeto](link-para-wiki)

## 📄 Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

---

**Desenvolvido com ❤️ usando Laravel + Docker + Bootstrap + JavaScript**

<p align="center">
  <strong>Sistema de Biblioteca - Gestão Inteligente de Acervo</strong>
</p>