#!/bin/bash

# Script de Atualização Rápida - Sistema de Checklist
# Execute no servidor: ./quick-update.sh

echo "=== ATUALIZAÇÃO RÁPIDA VIA GIT ==="
echo "Data: $(date)"

PROJECT_DIR="/home/devaxis-checklist/htdocs/checklist.devaxis.com.br"
cd $PROJECT_DIR

echo "🔄 Baixando últimas alterações..."
git pull origin main

echo "⚡ Limpando e recriando caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔒 Ajustando permissões..."
chmod -R 775 storage bootstrap/cache

echo "✅ ATUALIZAÇÃO CONCLUÍDA!"
echo "🌐 https://checklist.devaxis.com.br"