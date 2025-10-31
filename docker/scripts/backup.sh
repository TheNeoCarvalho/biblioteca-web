#!/bin/bash

# Script de backup automático do banco de dados
# Sistema de Biblioteca

set -e

# Configurações
BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="biblioteca_backup_${DATE}.sql"
RETENTION_DAYS=30

echo "🗄️  Iniciando backup do banco de dados..."

# Criar diretório de backup se não existir
mkdir -p $BACKUP_DIR

# Executar backup
mysqldump -h $MYSQL_HOST \
          -u $MYSQL_USER \
          -p$MYSQL_PASSWORD \
          --single-transaction \
          --routines \
          --triggers \
          --add-drop-table \
          --extended-insert \
          --quick \
          --lock-tables=false \
          $MYSQL_DATABASE > $BACKUP_DIR/$BACKUP_FILE

# Comprimir backup
gzip $BACKUP_DIR/$BACKUP_FILE

echo "✅ Backup criado: ${BACKUP_FILE}.gz"

# Remover backups antigos
find $BACKUP_DIR -name "biblioteca_backup_*.sql.gz" -mtime +$RETENTION_DAYS -delete

echo "🧹 Backups antigos removidos (mais de $RETENTION_DAYS dias)"

# Listar backups existentes
echo "📋 Backups disponíveis:"
ls -lh $BACKUP_DIR/biblioteca_backup_*.sql.gz 2>/dev/null || echo "Nenhum backup encontrado"

echo "✅ Backup concluído com sucesso!"