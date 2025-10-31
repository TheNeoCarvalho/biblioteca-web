#!/bin/bash

# Script de configuração inicial do Sistema de Biblioteca
# Execute este script após clonar o repositório

set -e

echo "🚀 Configurando Sistema de Biblioteca com Docker..."

# Verificar se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

# Verificar se o Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

# Criar diretórios necessários
echo "📁 Criando diretórios necessários..."
mkdir -p storage/logs/nginx
mkdir -p storage/logs/mysql
mkdir -p storage/app/public
mkdir -p bootstrap/cache
mkdir -p backups

# Copiar arquivo de ambiente
if [ ! -f .env ]; then
    echo "📄 Copiando arquivo de ambiente..."
    cp .env.docker .env
    echo "✅ Arquivo .env criado. Configure as variáveis conforme necessário."
else
    echo "⚠️  Arquivo .env já existe. Verifique as configurações."
fi

# Gerar chave da aplicação
echo "🔑 Gerando chave da aplicação..."
docker-compose run --rm app php artisan key:generate

# Construir e iniciar containers
echo "🐳 Construindo e iniciando containers..."
docker-compose up -d --build

# Aguardar o banco de dados ficar pronto
echo "⏳ Aguardando banco de dados ficar pronto..."
sleep 30

# Executar migrações
echo "🗄️  Executando migrações do banco de dados..."
docker-compose exec app php artisan migrate --force

# Executar seeders
echo "🌱 Executando seeders..."
docker-compose exec app php artisan db:seed --force

# Criar link simbólico para storage
echo "🔗 Criando link simbólico para storage..."
docker-compose exec app php artisan storage:link

# Limpar e otimizar cache
echo "🧹 Limpando e otimizando cache..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Definir permissões
echo "🔐 Definindo permissões..."
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chown -R www-data:www-data /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/storage
docker-compose exec app chmod -R 775 /var/www/bootstrap/cache

echo ""
echo "✅ Configuração concluída com sucesso!"
echo ""
echo "🌐 Acesse a aplicação em: http://localhost:8080"
echo "🗄️  phpMyAdmin em: http://localhost:8081"
echo "📧 Mailhog em: http://localhost:8025"
echo ""
echo "📋 Credenciais padrão:"
echo "   Email: admin@biblioteca.com"
echo "   Senha: admin123"
echo ""
echo "🐳 Comandos úteis:"
echo "   docker-compose up -d          # Iniciar containers"
echo "   docker-compose down           # Parar containers"
echo "   docker-compose logs app       # Ver logs da aplicação"
echo "   docker-compose exec app bash  # Acessar container da aplicação"
echo ""