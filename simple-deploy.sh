#!/bin/bash

# Script Simples de Deploy - Sistema de Checklist
# Execute no servidor: bash simple-deploy.sh

echo "=== DEPLOY SISTEMA CHECKLIST - VERSÃO SIMPLIFICADA ==="

# Configurar PATH
export PATH="/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:$PATH"

# Definir diretório
PROJECT_DIR="/home/devaxis-checklist/htdocs/checklist.devaxis.com.br"

echo "📁 Navegando para: $PROJECT_DIR"
cd $PROJECT_DIR

# Verificar se é primeira instalação ou atualização
if [ ! -d ".git" ]; then
    echo "🔄 Primeira instalação - clonando repositório..."
    git clone https://github.com/marcelov7/checklist.git .
else
    echo "🔄 Atualizando repositório existente..."
    git pull origin main
fi

# Configurar .env se não existir
if [ ! -f ".env" ]; then
    echo "⚙️ Criando arquivo .env..."
    cat > .env << 'EOL'
APP_NAME="Sistema de Checklist"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://checklist.devaxis.com.br

DB_CONNECTION=sqlite
DB_DATABASE=/home/devaxis-checklist/htdocs/checklist.devaxis.com.br/database/database.sqlite

SESSION_DRIVER=file
SESSION_LIFETIME=480

LOG_CHANNEL=stack
LOG_LEVEL=error

CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
EOL
fi

# Gerar chave se necessário
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Gerando chave da aplicação..."
    php artisan key:generate --force
fi

# Configurar permissões
echo "🔒 Configurando permissões..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Executar migrações
echo "🗄️ Executando migrações..."
php artisan migrate --force

# Otimizar aplicação
echo "⚡ Otimizando aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "✅ DEPLOY CONCLUÍDO!"
echo "🌐 https://checklist.devaxis.com.br"
echo ""