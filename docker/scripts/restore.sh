#!/bin/bash

# Script de restauração do banco de dados
# Sistema de Biblioteca

set -e

# Verificar se foi fornecido um arquivo de backup
if [ -z "$1" ]; then
    echo "❌ Uso: $0 <arquivo_backup.sql.gz>"
    echo "📋 Backups disponíveis:"
    ls -1 /backups/biblioteca_backup_*.sql.gz 2>/dev/null || echo "Nenhum backup encontrado"
    exit 1
fi

BACKUP_FILE="$1"

# Verificar se o arquivo existe
if [ ! -f "/backups/$BACKUP_FILE" ]; then
    echo "❌ Arquivo de backup não encontrado: $BACKUP_FILE"
    exit 1
fi

echo "🔄 Iniciando restauração do banco de dados..."
echo "📁 Arquivo: $BACKUP_FILE"

# Confirmar restauração
read -p "⚠️  Isso irá substituir todos os dados atuais. Continuar? (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Restauração cancelada."
    exit 1
fi

# Descomprimir e restaurar
echo "📤 Descomprimindo e restaurando backup..."

zcat "/backups/$BACKUP_FILE" | mysql -h $MYSQL_HOST \
                                    -u $MYSQL_USER \
                                    -p$MYSQL_PASSWORD \
                                    $MYSQL_DATABASE

echo "✅ Restauração concluída com sucesso!"
echo "🔄 Reinicie a aplicação para aplicar as mudanças."