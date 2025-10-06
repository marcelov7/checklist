#!/bin/bash

# Script de Atualização Rápida - Sistema de Checklist
# Execute no servidor: ./quick-update.sh

echo "=== ATUALIZAÇÃO RÁPIDA VIA GIT ==="
echo "Data: $(date)"

PROJECT_DIR="/home/devaxis-checklist/htdocs/checklist.devaxis.com.br"
cd $PROJECT_DIR

echo "🔄 Baixando últimas alterações..."
/usr/bin/git pull origin main

echo "⚡ Limpando e recriando caches..."
/usr/bin/php artisan config:clear
/usr/bin/php artisan cache:clear
/usr/bin/php artisan view:clear
/usr/bin/php artisan config:cache
/usr/bin/php artisan route:cache
/usr/bin/php artisan view:cache

echo "🔒 Ajustando permissões..."
/bin/chmod -R 775 storage bootstrap/cache

echo "✅ ATUALIZAÇÃO CONCLUÍDA!"
echo "🌐 https://checklist.devaxis.com.br"