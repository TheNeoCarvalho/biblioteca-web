# 🐳 Sistema de Biblioteca - Docker

Este guia explica como executar o Sistema de Biblioteca usando Docker.

## 📋 Pré-requisitos

- Docker (versão 20.10 ou superior)
- Docker Compose (versão 2.0 ou superior)
- Git

## 🚀 Instalação Rápida

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd sistema-biblioteca
```

### 2. Execute o script de configuração
```bash
chmod +x docker/scripts/setup.sh
./docker/scripts/setup.sh
```

### 3. Acesse a aplicação
- **Aplicação:** http://localhost:8080
- **phpMyAdmin:** http://localhost:8081
- **Mailhog:** http://localhost:8025

## 🔧 Configuração Manual

### 1. Copiar arquivo de ambiente
```bash
cp .env.docker .env
```

### 2. Gerar chave da aplicação
```bash
docker-compose run --rm app php artisan key:generate
```

### 3. Construir e iniciar containers
```bash
docker-compose up -d --build
```

### 4. Executar migrações e seeders
```bash
docker-compose exec app php artisan migrate --seed
```

### 5. Criar link simbólico para storage
```bash
docker-compose exec app php artisan storage:link
```

## 🐳 Containers Incluídos

| Container | Serviço | Porta | Descrição |
|-----------|---------|-------|-----------|
| `biblioteca_app` | Laravel/PHP-FPM | 9000 | Aplicação principal |
| `biblioteca_webserver` | Nginx | 8080 | Servidor web |
| `biblioteca_db` | MySQL 8.0 | 3306 | Banco de dados |
| `biblioteca_redis` | Redis | 6379 | Cache e sessões |
| `biblioteca_phpmyadmin` | phpMyAdmin | 8081 | Interface do banco |
| `biblioteca_mailhog` | Mailhog | 8025 | Captura de emails |

## 📊 Credenciais Padrão

### Aplicação
- **Email:** admin@biblioteca.com
- **Senha:** admin123

### Banco de Dados
- **Host:** localhost:3306
- **Database:** sistema_biblioteca
- **Username:** biblioteca_user
- **Password:** biblioteca_pass_123

### phpMyAdmin
- **Username:** biblioteca_user
- **Password:** biblioteca_pass_123

## 🛠️ Comandos Úteis

### Gerenciamento de Containers
```bash
# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Reiniciar containers
docker-compose restart

# Ver logs
docker-compose logs -f app

# Acessar container da aplicação
docker-compose exec app bash
```

### Comandos Laravel
```bash
# Executar migrações
docker-compose exec app php artisan migrate

# Executar seeders
docker-compose exec app php artisan db:seed

# Limpar cache
docker-compose exec app php artisan cache:clear

# Gerar chave da aplicação
docker-compose exec app php artisan key:generate

# Executar testes
docker-compose exec app php artisan test
```

### Comandos de Banco de Dados
```bash
# Acessar MySQL
docker-compose exec db mysql -u biblioteca_user -p sistema_biblioteca

# Backup do banco
docker-compose exec db mysqldump -u biblioteca_user -p sistema_biblioteca > backup.sql

# Restaurar backup
docker-compose exec -T db mysql -u biblioteca_user -p sistema_biblioteca < backup.sql
```

## 🗄️ Backup e Restauração

### Backup Automático
```bash
# Executar backup manual
docker-compose run --rm backup

# Agendar backup (crontab)
0 2 * * * cd /path/to/projeto && docker-compose run --rm backup
```

### Restauração
```bash
# Listar backups disponíveis
ls -la backups/

# Restaurar backup específico
docker/scripts/restore.sh biblioteca_backup_20231201_020000.sql.gz
```

## 🔧 Configurações Avançadas

### Variáveis de Ambiente (.env)
```env
# Configurações da aplicação
APP_NAME="Sistema de Biblioteca"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

# Banco de dados
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=sistema_biblioteca
DB_USERNAME=biblioteca_user
DB_PASSWORD=biblioteca_pass_123

# Cache e sessões
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=redis

# Email (Mailhog)
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

### Personalizar Configurações

#### PHP (docker/php/local.ini)
```ini
upload_max_filesize = 40M
post_max_size = 40M
memory_limit = 256M
```

#### Nginx (docker/nginx/conf.d/app.conf)
```nginx
client_max_body_size 40M;
```

#### MySQL (docker/mysql/my.cnf)
```ini
max_connections = 200
innodb_buffer_pool_size = 256M
```

## 🚀 Produção

### Usar configuração de produção
```bash
# Usar docker-compose para produção
docker-compose -f docker-compose.prod.yml up -d

# Configurar variáveis de ambiente
cp .env.production .env
```

### SSL/HTTPS
1. Coloque os certificados em `docker/nginx/ssl/`
2. Configure o Nginx para usar HTTPS
3. Atualize `APP_URL` para https

## 🐛 Solução de Problemas

### Container não inicia
```bash
# Ver logs detalhados
docker-compose logs container_name

# Reconstruir containers
docker-compose down
docker-compose up -d --build --force-recreate
```

### Problemas de permissão
```bash
# Corrigir permissões
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage
```

### Banco de dados não conecta
```bash
# Verificar se o container do banco está rodando
docker-compose ps

# Testar conexão
docker-compose exec app php artisan tinker
>>> DB::connection()->getPdo();
```

### Limpar tudo e recomeçar
```bash
# Parar e remover containers, volumes e imagens
docker-compose down -v --rmi all

# Remover volumes órfãos
docker volume prune

# Reconstruir tudo
docker-compose up -d --build
```

## 📚 Recursos Adicionais

- [Documentação do Docker](https://docs.docker.com/)
- [Documentação do Laravel](https://laravel.com/docs)
- [Docker Compose Reference](https://docs.docker.com/compose/)

## 🆘 Suporte

Se encontrar problemas:

1. Verifique os logs: `docker-compose logs -f`
2. Consulte a seção de solução de problemas
3. Abra uma issue no repositório

---

**Desenvolvido com ❤️ usando Docker + Laravel + MySQL + Redis + Nginx**